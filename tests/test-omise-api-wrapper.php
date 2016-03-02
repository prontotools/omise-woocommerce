<?php

require_once( "omise-api-wrapper.php" );

class Omise_Test extends WP_UnitTestCase {
    public function setUp() {
        parent::setUp();
    }

    function test_create_charge_should_call_method_call_api_with_required_params() {
        $omise = $this->getMockBuilder( "Omise" )
            ->setMethods( array( "call_api" ) )
            ->getMock();

        $result = '{
            "object": "charge",
            "id": "chrg_test_id",
            "livemode": false,
            "location": "/charges/chrg_test_id",
            "amount": 500,
            "currency": "thb",
            "description": "Charge for order 3947",
            "status": "successful",
            "capture": true,
            "authorized": true,
            "paid": true,
            "transaction": "trxn_test_id",
            "refunded": 0,
            "refunds": {
                "object": "list",
                "from": "1970-01-01T00:00:00+00:00",
                "to": "2016-03-02T08:30:51+00:00",
                "offset": 0,
                "limit": 20,
                "total": 0,
                "order": null,
                "location": "/charges/chrg_test_id/refunds",
                "data": []
            },
            "return_uri": "http://www.example.com/orders/3947/complete",
            "reference": "paym_test_id",
            "authorize_uri": "https://api.omise.co/payments/paym_test_id/authorize",
            "failure_code": null,
            "failure_message": null,
            "card": {
                "object": "card",
                "id": "card_test_536y8y2ghduzbchcqun",
                "livemode": false,
                "location": "/customers/customer_id/cards/card_test_id",
                "country": "us",
                "city": null,
                "postal_code": null,
                "financing": "",
                "bank": "JPMORGAN CHASE BANK, N.A.",
                "last_digits": "1111",
                "brand": "Visa",
                "expiration_month": 12,
                "expiration_year": 2019,
                "fingerprint": "test_fingerprint",
                "name": "Pronto Tools",
                "security_code_check": true,
                "created": "2016-03-01T04:27:00Z"
            },
            "customer": "customer_id",
            "ip": null,
            "dispute": null,
            "created": "2016-03-02T08:30:51Z"
        }';

        $chargeInfo = array(
            "amount"      => 500,
            "currency"    => "thb",
            "description" => "Charge for order 3947",
            "return_uri"  => add_query_arg(
                "order_id",
                3947,
                "http://www.example.com/?wc-api=wc_gateway_omise"
            )
        );

        $omise->expects( $this->once() )
            ->method( "call_api" )
            ->with( "private_key", "POST", "/charges", $chargeInfo )
            ->will( $this->returnValue( $result ) );

        $expected = json_decode( $result );
        $actual = $omise->create_charge( "private_key", $chargeInfo );

        $this->assertEquals( $expected, $actual );
    }

    function test_create_card_should_call_method_call_api_with_required_params() {
        $omise = $this->getMockBuilder( "Omise" )
            ->setMethods( array( "call_api" ) )
            ->getMock();

        $result = '{
            "object": "token",
            "id": "tokn_test_id",
            "livemode": false,
            "location": "https://vault.omise.co/tokens/tokn_test_id",
            "used": false,
            "card": {
                "object": "card",
                "id": "card_test_id",
                "livemode": false,
                "country": "us",
                "city": "Bangkok",
                "postal_code": "10320",
                "financing": "",
                "bank": "",
                "last_digits": "4242",
                "brand": "Visa",
                "expiration_month": 3,
                "expiration_year": 2018,
                "fingerprint": "test_fingerprint",
                "name": "JOHN DOE",
                "security_code_check": true,
                "created": "2016-03-02T08:54:53Z"
            },
            "created": "2016-03-02T08:54:53Z"
        }';

        $omise->expects( $this->once() )
            ->method( "call_api" )
            ->with( "private_key", "PATCH", "/customers/customer_id", "card=test_toker" )
            ->will( $this->returnValue( $result ) );

        $expected = json_decode( $result );
        $actual = $omise->create_card( "private_key", "customer_id", "test_toker" );

        $this->assertEquals( $expected, $actual );
    }
}
