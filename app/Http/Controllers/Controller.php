<?php

namespace App\Http\Controllers;

use acme\common\ACME;
use acme\common\Exception;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected function check(Request $request)
    {
        return $this->validate($request, [
            'fqdn' => 'required',
            'value' => 'required'
        ]);
    }

    /**
     * @param string $fqdn
     * @return \acme\common\ClientInterface
     * @throws Exception
     */
    protected function getProvider(string $fqdn): \acme\common\ClientInterface
    {
        $config = config("acme");

        $splitDomain = explode(".", $fqdn);
        $domain = implode(".", array_slice($splitDomain, -3, 2));

        if (!isset($config["domains"][$domain])) {
            throw new Exception("Domain " . $domain . " not found in acme config");
        }

        $provider = $config["domains"][$domain];
        $providerConfig = $config["providers"][$provider] ?? [];

        return ACME::create($provider, $providerConfig);
    }

    public function present(Request $request)
    {
        $data = $this->check($request);

        $provider = $this->getProvider($data["fqdn"]);
        $provider->present($data["fqdn"], $data["value"]);
        echo $this->getResponse($data["fqdn"], $data["value"]);
    }

    public function cleanup(Request $request)
    {
        $data = $this->check($request);
        $provider = $this->getProvider($data["fqdn"]);
        $provider->cleanUp($data["fqdn"], $data["value"]);
        echo $this->getResponse($data["fqdn"], $data["value"]);
    }

    protected function getResponse(string $fqdn, string $txt)
    {
        // Send back the original JSON to confirm success
        return json_encode([
            "fqdn" => $fqdn,
            "value" => $txt,
        ]);
    }
}
