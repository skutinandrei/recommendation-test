<?php 

class GetProductRecommendationsCest
{
    public function _before(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
    }

    // tests

    public function tryToGetRecommendationsWithLimit(\ApiTester $I)
    {
        $I->getProductRecommendations('LO019EMJGZ27', 2);
        $I->seeResponseCodeIs(200);
        $response = json_decode($I->grabResponse());
        $I->assertEquals(2, count($response)); 
        $I->seeResponseContainsJson(["product" => ["sku" => "LO019EMJGZ29"]]);
        $I->seeResponseContainsJson(["product" => ["sku" => "FI015EMCUI06"]]);
    }

    public function tryToGetRecommendationsWithoutLimit(\ApiTester $I)
    {
        $I->getProductRecommendationsWithoutLimit('LO019EMJGZ27');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson(["product" => ["sku" => "LO019EMJGZ29"]]);
        $I->seeResponseContainsJson(["product" => ["sku" => "FI015EMCUI06"]]);
        $I->seeResponseContainsJson(["product" => ["sku" => "TO030EMRUE89"]]);
    }

    public function tryToGetRecommendationsWithWrongSku(\ApiTester $I)
    {
        $I->getProductRecommendations('incorrectSku', 2);
        $I->seeResponseCodeIs(200);
        $I->seeResponseEquals('[]');
    }

    public function tryToGetRecommendationsWithEmptySku(\ApiTester $I)
    {
        $I->getProductRecommendations('', 2);
        $I->seeResponseCodeIs(400);
        $I->seeResponseContainsJson(["faultcode" => "Client.ValidationError"]);
        $I->seeResponseContainsJson(["faultstring" => "The value \"u''\" could not be validated."]);
    }

    public function tryToGetRecommendationsWithoutSku(\ApiTester $I)
    {
        $I->getProductRecommendationsWithoutSku(2);
        $I->seeResponseCodeIs(400);
        $I->seeResponseContainsJson(["faultcode" => "Client.ValidationError"]);
        $I->seeResponseContainsJson(["faultstring" => "\"'sku'\" member must occur at least 1 times."]);
    }

}

