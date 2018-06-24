<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

use AppBundle\Controller\Api\ApiControllerInterface;

class BaseApiController extends Controller implements ApiControllerInterface
{
	protected $apiKey;
	protected $accessTokenInfo;
	protected $accessToken;
	protected $apiVersion;

	public function validateRequest()
	{
		$request = Request::createFromGlobals();
		$headers = $request->headers->all();
		
		if (!isset($headers['x-api-request'])) {
            throw new CustomUserMessageAuthenticationException('Invalid Request.');
        }

		$this->validateApiKeyChain($headers);

	}

	protected function validateApiKeyChain($headers) 
	{
		if (!$this->container->getParameter('api_keys')) {
			throw new CustomUserMessageAuthenticationException('No defined keys in parameters.yml');
		}

		$apiKeys = $this->container->getParameter('api_keys');

		if (!isset($headers['api-key']) || !in_array($headers['api-key'][0], $apiKeys)) {
			throw new CustomUserMessageAuthenticationException(sprintf('API Key "%s" does not exist.', $headers['api-key'][0]));
		}
	}
}