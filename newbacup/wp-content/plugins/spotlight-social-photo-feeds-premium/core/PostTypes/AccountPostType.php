<?php

namespace RebelCode\Spotlight\Instagram\PostTypes;

use RebelCode\Spotlight\Instagram\IgApi\AccessToken;
use RebelCode\Spotlight\Instagram\IgApi\IgAccount;
use RebelCode\Spotlight\Instagram\IgApi\IgUser;
use RebelCode\Spotlight\Instagram\MediaStore\MediaSource;
use RebelCode\Spotlight\Instagram\Wp\PostType;
use RuntimeException;
use WP_Error;
use WP_Post;

/**
 * The post type for accounts.
 * 
 * This class extends the {@link PostType} class only as a formality. The primary purpose of this class is to house
 * the meta key constants and functionality for dealing with posts of the account custom post type.
 *
 * @since 0.1
 */
class AccountPostType extends PostType
{
    const USER_ID = '_sli_user_id';
    const USERNAME = '_sli_username';
    const NAME = '_sli_name';
    const BIO = '_sli_bio';
    const TYPE = '_sli_account_type';
    const MEDIA_COUNT = '_sli_media_count';
    const PROFILE_PIC_URL = '_sli_profile_pic_url';
    const FOLLOWERS_COUNT = '_sli_followers_count';
    const FOLLOWS_COUNT = '_sli_follows_count';
    const WEBSITE = '_sli_website';
    const CUSTOM_PROFILE_PIC = '_sli_custom_profile_pic';
    const CUSTOM_BIO = '_sli_custom_bio';
    const ACCESS_TOKEN = '_sli_access_token';
    const ACCESS_EXPIRY = '_sli_access_expires';

    /**
     * Converts a WordPress post into an IG account instance.
     *
     * @since 0.1
     *
     * @param WP_Post $post
     *
     * @return IgAccount
     */
    public static function fromWpPost(WP_Post $post) : IgAccount
    {
        $accessToken = new AccessToken(
            $post->{static::ACCESS_TOKEN},
            $post->{static::ACCESS_EXPIRY}
        );

        $user = IgUser::create([
            'id' => $post->{static::USER_ID},
            'username' => $post->{static::USERNAME},
            'name' => $post->{static::NAME},
            'biography' => $post->{static::BIO},
            'account_type' => $post->{static::TYPE},
            'media_count' => $post->{static::MEDIA_COUNT},
            'profile_picture_url' => $post->{static::PROFILE_PIC_URL},
            'followers_count' => $post->{static::FOLLOWERS_COUNT},
            'follows_count' => $post->{static::FOLLOWS_COUNT},
            'website' => $post->{static::WEBSITE},
        ]);

        return new IgAccount($user, $accessToken);
    }

    /**
     * Converts an IG account instance into a WordPress post.
     *
     * @since 0.1
     *
     * @param IgAccount $account
     *
     * @return array
     */
    public static function toWpPost(IgAccount $account) : array
    {
        $user = $account->getUser();
        $accessToken = $account->getAccessToken();

        return [
            'post_title' => $user->getUsername(),
            'post_status' => 'publish',
            'meta_input' => [
                static::USER_ID => $user->getId(),
                static::USERNAME => $user->getUsername(),
                static::NAME => $user->getName(),
                static::BIO => $user->getBio(),
                static::TYPE => $user->getType(),
                static::MEDIA_COUNT => $user->getMediaCount(),
                static::PROFILE_PIC_URL => $user->getProfilePicUrl(),
                static::FOLLOWERS_COUNT => $user->getFollowersCount(),
                static::FOLLOWS_COUNT => $user->getFollowsCount(),
                static::WEBSITE => $user->getWebsite(),
                static::ACCESS_TOKEN => $accessToken->getCode(),
                static::ACCESS_EXPIRY => $accessToken->getExpires(),
            ],
        ];
    }

    /**
     * Converts a WordPress post into a post array for an account.
     *
     * @since 0.1
     *
     * @param WP_Post $post The post.
     *
     * @return array The post array.
     */
    public static function fromWpPostToArray(WP_Post $post) : array
    {
        $array = static::toWpPost(static::fromWpPost($post));

        $array['meta_input'][static::CUSTOM_PROFILE_PIC] = $post->{static::CUSTOM_PROFILE_PIC};
        $array['meta_input'][static::CUSTOM_BIO] = $post->{static::CUSTOM_BIO};

        return $array;
    }

    /**
     * Finds and retrieves a business account.
     *
     * @since 0.1
     *
     * @param PostType $cpt The post type instance.
     *
     * @return IgAccount|null The found business account, or null if none could be found.
     */
    public static function findBusinessAccount(PostType $cpt)
    {
        $accounts = $cpt->query([
            'meta_query' => [
                [
                    'key' => AccountPostType::TYPE,
                    'value' => IgUser::TYPE_BUSINESS,
                ],
            ],
        ]);

        return count($accounts) > 0
            ? AccountPostType::fromWpPost($accounts[0])
            : null;
    }

    /**
     * Inserts an account into the database, or updates an existing account if it already exists in the database.
     *
     * @since 0.1
     *
     * @param PostType  $cpt     The post type.
     * @param IgAccount $account The account instance.
     *
     * @return int The inserted ID.
     *
     * @throws RuntimeException If the insertion or update failed.
     */
    public static function insertOrUpdate(PostType $cpt, IgAccount $account)
    {
        $existing = $cpt->query([
            'meta_query' => [
                [
                    'key' => static::USER_ID,
                    'value' => $account->getUser()->getId(),
                ],
            ],
        ]);

        $data = static::toWpPost($account);

        $result = (count($existing) > 0)
            ? $cpt->update($existing[0]->ID, $data)
            : $cpt->insert($data);

        if ($result instanceof WP_Error) {
            throw new RuntimeException($result->get_error_message());
        }

        return $result;
    }

    /**
     * Deletes an account and its associated media from the DB.
     *
     * @since 0.1
     *
     * @param string   $id          The ID of the account to delete.
     * @param PostType $accountsCpt The accounts post type.
     * @param PostType $mediaCpt    The media post type.
     *
     * @return bool True on success, false on failure.
     */
    public static function deleteWithMedia($id, PostType $accountsCpt, PostType $mediaCpt)
    {
        // Make sure the account exists
        $post = $accountsCpt->get($id);
        if ($post === null) {
            return false;
        }

        // Delete associated media
        static::deleteAccountMedia($id, $accountsCpt, $mediaCpt);

        // Delete the account
        $result = $accountsCpt->delete($id);
        if ($result === false) {
            return false;
        }

        return true;
    }

    /**
     * Deletes all media associated with an account, by ID.
     *
     * @since 0.1
     *
     * @param string   $id          The ID of the account to delete.
     * @param PostType $accountsCpt The accounts post type.
     * @param PostType $mediaCpt    The media post type.
     *
     * @return bool True on success, false on failure.
     */
    public static function deleteAccountMedia($id, PostType $accountsCpt, PostType $mediaCpt)
    {
        // Make sure the account exists
        $post = $accountsCpt->get($id);
        if ($post === null) {
            return false;
        }

        // Get the source for the account's user
        $account = static::fromWpPost($post);
        $user = $account->getUser();
        $source = MediaSource::forUser($user);

        MediaPostType::deleteForSource($source, $mediaCpt);

        return true;
    }
}
