<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8 ;">
    <meta http-equiv="Content-Security-Policy" content="default-src *;
   img-src * 'self' data: https:; script-src 'self' 'unsafe-inline' 'unsafe-eval' *;
   style-src  'self' 'unsafe-inline' *;font-src 'self';">
    <style>
        body, html {
            padding: 0;
            margin: 0;
            z-index: 99;
        }
        @font-face {
			font-family: 'Gotham Regular';
			src: url('fonts/Gotham-Light.eot');
			src: url('fonts/Gotham-Light.eot?#iefix') format('embedded-opentype'),
			url('fonts/Gotham-Light.woff2') format('woff2'),
			url('fonts/Gotham-Light.woff') format('woff'),
			url('fonts/Gotham-Light.ttf') format('truetype'),
			url('fonts/Gotham-Light.svg#Gotham-Light') format('svg');
			font-weight: 300;
			font-style: normal;
			font-display: swap;
		}

		@font-face {
			font-family: 'Gotham Regular';
			src: url('fonts/Gotham-Book.eot');
			src: url('fonts/Gotham-Book.eot?#iefix') format('embedded-opentype'),
			url('fonts/Gotham-Book.woff2') format('woff2'),
			url('fonts/Gotham-Book.woff') format('woff'),
			url('fonts/Gotham-Book.ttf') format('truetype'),
			url('fonts/Gotham-Book.svg#Gotham-Book') format('svg');
			font-weight: normal;
			font-style: normal;
			font-display: swap;
		}

		@font-face {
			font-family: 'Gotham Regular';
			src: url('fonts/Gotham-Medium.eot');
			src: url('fonts/Gotham-Medium.eot?#iefix') format('embedded-opentype'),
			url('fonts/Gotham-Medium.woff2') format('woff2'),
			url('fonts/Gotham-Medium.woff') format('woff'),
			url('fonts/Gotham-Medium.ttf') format('truetype'),
			url('fonts/Gotham-Medium.svg#Gotham-Medium') format('svg');
			font-weight: 500;
			font-style: normal;
			font-display: swap;
		}

		@font-face {
			font-family: 'Gotham Regular';
			src: url('fonts/Gotham-Bold.eot');
			src: url('fonts/Gotham-Bold.eot?#iefix') format('embedded-opentype'),
			url('fonts/Gotham-Bold.woff2') format('woff2'),
			url('fonts/Gotham-Bold.woff') format('woff'),
			url('fonts/Gotham-Bold.ttf') format('truetype'),
			url('fonts/Gotham-Bold.svg#Gotham-Bold') format('svg');
			font-weight: bold;
			font-style: normal;
			font-display: swap;
		}

		@font-face {
			font-family: 'Gotham Bold';
			src: url('fonts/Gotham-Bold.eot');
			src: url('fonts/Gotham-Bold.eot?#iefix') format('embedded-opentype'),
			url('fonts/Gotham-Bold.woff2') format('woff2'),
			url('fonts/Gotham-Bold.woff') format('woff'),
			url('fonts/Gotham-Bold.ttf') format('truetype'),
			url('fonts/Gotham-Bold.svg#Gotham-Bold') format('svg');
			font-weight: bold;
			font-style: normal;
			font-display: swap;
		}
        .wpmail-container {
            width: 100%;
            background-color: #F3F3F3;
        }
        .wpmail-container p,
        .wpmail-container h5 {
            margin: 0px;
        }
        .wpmail-container a.maillink {
            color: #2F80ED;
        }

        /* HEADER */
        .wpmail-logo {
            padding: 8px 0px 16px 0px;
            text-align: center;
        }
        .wpmail-logo img {
            width: 174px;
            height: 76px;
        }
        .wpmail-line {
            display: block;
            position: relative;
        }
        .wpmail-line:before {
            position: absolute;
            content: "";
            width: 370px;
            border-top: 1px solid #914204;
            margin-left: -185px;
        }

        /* CONTENT */
        .wpmail-content {
            position: relative;
            padding: 24px 24px 69px 24px;
            width: 568px;
            /* height: 430px; */
            margin: auto;
            background-color: #FFFFFF;
            border-radius: 4px;
            box-shadow: 0 4px 4px rgba(0,0,0,.25);
						box-sizing: border-box;
						overflow: hidden;
        }
        .wpmail-content .wpmail-text-header {
            font-family: Roboto, RobotoDraft, Helvetica, Arial, sans-serif, -apple-system, san-serif;
            font-weight: bold;
            font-size: 24px;
            line-height: 23px;
            text-align: center;
            letter-spacing: 0.5px;
            color: #3F3F3F;
            margin-bottom: 8px;
        }
        .wpmail-content .wpmail-text-header span {
            color: #3C1605;
        }
        .wpmail-content .wpmail-pharse-title {
            font-family: Roboto, RobotoDraft, Helvetica, Arial, sans-serif, -apple-system, san-serif;
            font-weight: bold;
            font-size: 20px;
            line-height: 28px;
            letter-spacing: 0.15px;
            color: #3C1605;
        }
        .wpmail-content .wpmail-text-regular {
            font-family: Roboto, RobotoDraft, Helvetica, Arial, sans-serif, -apple-system, san-serif;
            font-style: normal;
            font-weight: normal;
            font-size: 14px;
            line-height: 22px;
            letter-spacing: -0.25px;
            color: #3F3F3F;
        }
        .wpmail-shopping {
            text-align: center;
            z-index: 999;
            position: relative
        }
        .wpmail-button-shopping-text {
            margin: 24px auto;
            width: 181px;
            height: 49px;
            /*padding: 16px 31px;*/
            padding: 16px 4px;
            /* Primary/Brown */
            background: #914204;
            box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
            border-radius: 4px;
            box-sizing: border-box;
            color: white !important;
            text-decoration: none;
            cursor: pointer;
            display: block;
            font-family: Roboto, RobotoDraft, Helvetica, Arial, sans-serif , -apple-system, san-serif;
            font-weight: bold;
            font-size: 16px;
            line-height: 17px;
            /* identical to box height */
            letter-spacing: 0.75px;
            text-transform: uppercase;
        }
        .wpmail-section-footerbg {
            position: absolute;
            bottom: -30px;
            width: 100%;
            height: 139px;
            margin: 0px -24px;
            background-image: url(<?php echo get_template_directory_uri(); ?>/images/emails/fruits.png);
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            z-index: 0;
        }

        /* Footer */
        .wpmail-footer {
            text-align: center;
            padding: 58px 0px 36px 0px;
        }
        .wpmail-followus {
            font-family: Roboto, RobotoDraft, Helvetica, Arial, sans-serif , -apple-system, san-serif;
            font-style: normal;
            font-weight: bold;
            font-size: 20px;
            line-height: 28px;
            /* identical to box height, or 140% */
            text-align: center;
            letter-spacing: 0.15px;
            margin-bottom: 12px !important;
            color: #000000;
        }
         .wpmail-social {
            width: 140px;
            display: inline-block;
            height: 36px;
            margin-bottom: 16px;
        }
		.wpmail-social a{
			float: left;
		}
        .wpmail-info {
            font-family: Roboto, RobotoDraft, Helvetica, Arial, sans-serif, -apple-system, san-serif;
            font-style: normal;
            font-weight: 300;
            font-size: 12px;
            line-height: 16px;
            text-align: center;
            color: #3F3F3F;
        }
        .wpmail-info-fax {
            margin-left: 37px;
        }
    </style>
</head>
<body>
    <div class="wpmail-container">
        <div class="wpmail-logo">
            <img src="<?php echo get_template_directory_uri(); ?>/images/emails/logo.png">
            <span class="wpmail-line"></span>
        </div>
        <div class="wpmail-content">
            <div style="position: relative;z-index: 999;">
                <p class="wpmail-text-header">Hi <span><?php echo esc_html(get_user_meta($user_id, 'first_name', true)) ?></span></p>
                <p class="wpmail-text-header" style="margin-bottom: 24px;">Did you forget your password?</p>
                <p class="wpmail-text-regular" style="margin-bottom: 12px;text-align: center">To reset your password, kindly click the button below:</p>
                <div class="wpmail-shopping" style="margin-bottom: 24px;">
                    <a class="wpmail-button-shopping-text" target="_blank" href="<?php echo esc_url( add_query_arg( array( 'key' => $reset_key, 'id' => $user_id ), wc_get_endpoint_url( 'lost-password', '', wc_get_page_permalink( 'myaccount' ) ) ) ); ?>">RESET PASSWORD</a>
                </div>
                <p class="wpmail-text-regular" style="text-align: center">Alternatively, copy and paste this URL into your browser:</p>
                <p class="wpmail-text-regular" style="margin-bottom: 36px;text-align: center">
                    <a class="maillink" href="<?php echo esc_url( add_query_arg( array( 'key' => $reset_key, 'id' => $user_id ), wc_get_endpoint_url( 'lost-password', '', wc_get_page_permalink( 'myaccount' ) ) ) ); ?>" target="_blank">
											<?php echo esc_url( add_query_arg( array( 'key' => $reset_key, 'id' => $user_id ), wc_get_endpoint_url( 'lost-password', '', wc_get_page_permalink( 'myaccount' ) ) ) ); ?>
										</a>
                </p>
                <p class="wpmail-pharse-title">Didn’t request for this?</p>
                <p class="wpmail-text-regular">
					Please contact us at  <a class="maillink" href="mailto:contactus@cedeledepot.com">contactus@cedeledepot.com</a>. Our team will assist as soon as we can.
                </p>
            </div>
            <div class="wpmail-section-footerbg"></div>
        </div>
        <div class="wpmail-footer">
            <p class="wpmail-followus">Follow Us</p>
            <div class="wpmail-social">
                <a href="https://www.facebook.com/cedelesingapore" target="_blank">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/emails/social1.png">
                </a>
                <a href="https://www.instagram.com/cedelesingapore" target="_blank">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/emails/social2.png">
                </a>
				<a href="https://t.me/cedeletelegram" target="_blank">
					<img src="<?php echo get_template_directory_uri(); ?>/images/emails/social3.png">
				</a>
            </div>
            <p class="wpmail-info">1 Kaki Bukit Road 1, #02-41, Enterprise One, Singapore 415934</p>
            <p class="wpmail-info">
                Tel: 6922 9700 <span class="wpmail-info-fax">Fax: 6448 0035</span>
            </p>
        </div>
    </div>
</body>
</html>
