<?php

require_once( "omise-util.php" );

class Omise_Util_Test extends WP_UnitTestCase {
    public function setUp() {
        parent::setUp();
    }

    public function tearDown() {
        $_SERVER["HTTP_CLIENT_IP"]       = "";
        $_SERVER["HTTP_X_FORWARDED_FOR"] = "";
        $_SERVER["HTTP_X_FORWARDED"]     = "";
        $_SERVER["HTTP_FORWARDED_FOR"]   = "";
        $_SERVER["HTTP_FORWARDED"]       = "";
        $_SERVER["REMOTE_ADDR"]          = "";
    }

    function test_get_client_ip_should_return_ip_from_http_client_ip_if_set() {
        $_SERVER["HTTP_CLIENT_IP"] = "192.168.1.1";

        $expected = "192.168.1.1";
        $ip = Omise_Util::get_client_ip();
        $this->assertEquals( $expected, $ip );
    }

    function test_get_client_ip_should_return_ip_from_http_x_forwarded_for_if_set() {
        $_SERVER["HTTP_X_FORWARDED_FOR"] = "192.168.1.1";

        $expected = "192.168.1.1";
        $ip = Omise_Util::get_client_ip();
        $this->assertEquals( $expected, $ip );
    }

    function test_get_client_ip_should_return_ip_from_http_x_forwarded_if_set() {
        $_SERVER["HTTP_X_FORWARDED"] = "192.168.1.1";

        $expected = "192.168.1.1";
        $ip = Omise_Util::get_client_ip();
        $this->assertEquals( $expected, $ip );
    }

    function test_get_client_ip_should_return_ip_from_http_forwarded_for_if_set() {
        $_SERVER["HTTP_FORWARDED_FOR"] = "192.168.1.1";

        $expected = "192.168.1.1";
        $ip = Omise_Util::get_client_ip();
        $this->assertEquals( $expected, $ip );
    }

    function test_get_client_ip_should_return_ip_from_http_forwarded_if_set() {
        $_SERVER["HTTP_FORWARDED"] = "192.168.1.1";

        $expected = "192.168.1.1";
        $ip = Omise_Util::get_client_ip();
        $this->assertEquals( $expected, $ip );
    }

    function test_get_client_ip_should_return_ip_from_remote_addr_if_set() {
        $_SERVER["REMOTE_ADDR"] = "192.168.1.1";

        $expected = "192.168.1.1";
        $ip = Omise_Util::get_client_ip();
        $this->assertEquals( $expected, $ip );
    }

    function test_get_client_ip_should_return_unknown_if_server_variable_is_not_set() {
        $expected = "UNKNOWN";
        $ip = Omise_Util::get_client_ip();
        $this->assertEquals( $expected, $ip );
    }

    function test_render_view_should_require_view_path_and_render_it_correctly() {
        $viewPath = "includes/templates/omise-payment-form.php";

        ob_start();
        Omise_Util::render_view( $viewPath, NULL );
        $actual = ob_get_clean();

        $expected = '<div id="omise_cc_form">';
        $this->assertContains( $expected, $actual );

        $expected = '<fieldset id="new_card_form" class="">';
        $this->assertContains( $expected, $actual );

        $expected = '<label for="omise_card_name">Card Holder Name <span class="required">*</span></label>';
        $this->assertContains( $expected, $actual );
        $expected = '<input id="omise_card_name" class="input-text" type="text"';
        $this->assertContains( $expected, $actual );
        $expected = 'maxlength="255" autocomplete="off" placeholder="Card Holder Name"';
        $this->assertContains( $expected, $actual );
        $expected = 'name="omise_card_name">';
        $this->assertContains( $expected, $actual );

        $expected = '<label for="omise_card_number">Card Number <span class="required">*</span></label>';
        $this->assertContains( $expected, $actual );
        $expected = '<input id="omise_card_number" class="input-text" type="text"';
        $this->assertContains( $expected, $actual );
        $expected = 'maxlength="20" autocomplete="off" placeholder="Card Number"';
        $this->assertContains( $expected, $actual );
        $expected = 'name="omise_card_number">';
        $this->assertContains( $expected, $actual );

        $expected = '<label for="omise_card_expiration_month">Expiration Month <span';
        $this->assertContains( $expected, $actual );
        $expected = '<select id="omise_card_expiration_month" name="omise_card_expiration_month">';
        $this->assertContains( $expected, $actual );

        $months = array( "01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12" );
        foreach ( $months as $each ) {
            $expected = '<option value="' . $each . '">' . $each . '</option>';
            $this->assertContains( $expected, $actual );
        }

        $expected = '<label for="omise_card_expiration_year">Expiration Year <span';
        $this->assertContains( $expected, $actual );
        $expected = '<select id="omise_card_expiration_year" name="omise_card_expiration_year">';
        $this->assertContains( $expected, $actual );

        $this_year = (int) date( "Y" );
        for ( $year = $this_year; $year <= $this_year + 10; $year++ ) {
            $expected = '<option value="' . $each . '">' . $each . '</option>';
            $this->assertContains( $expected, $actual );
        }

        $expected = '<label for="omise_card_security_code">Security Code <span';
        $this->assertContains( $expected, $actual );
        $expected = '<input id="omise_card_security_code"';
        $this->assertContains( $expected, $actual );
        $expected = 'class="input-text" type="password" autocomplete="off"';
        $this->assertContains( $expected, $actual );
        $expected = 'placeholder="CVC" name="omise_card_security_code">';
        $this->assertContains( $expected, $actual );
    }

    function test_render_json_error_with_message_should_return_json_with_error_message() {
        $expected = '"{ \"object\": \"error\", \"message\": \"omise_token is required\" }"';

        ob_start();
        Omise_Util::render_json_error( "omise_token is required" );
        $actual = ob_get_clean();

        $this->assertEquals( $expected, $actual );
    }
}
