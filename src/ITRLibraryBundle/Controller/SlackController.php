<?php

namespace ITRLibraryBundle\Controller;

use ITRLibraryBundle\Entity\Subscriber;
use ITRLibraryBundle\Events\PostEvent;
use ITRLibraryBundle\Events\PostEvents;
use ITRLibraryBundle\Form\SubscriberType;
use ITRLibraryBundle\Service\CommandResponse;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ITRLibraryBundle\Entity\Post;
use ITRLibraryBundle\Form\PostType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Slack command controller.
 *
 * @Route("/slack")
 */
class SlackController extends Controller
{

    /**
     * Creates a new Post entity from Slack.
     * Usage: /library url [title] [tag1,tag2,...]
     *
     * @Route("/{command}", name="slack_command")
     * @Method({"POST", "GET"})
     *
     * @return JsonResponse
     */
    public function commandAction(Request $request, string $command)
    {
        //TODO: Remove GET

        try {
            $line = $request->query->get('text');
            $token = $request->query->get('token');

            $cmdService = $this->get('itrlibrary.service.command');

            return $this->json([
                $cmdService->processCommand($command, $line, $token)->getSlackResponse(),
            ]);

        } catch (\Exception $exception) {
            $response = new CommandResponse($exception->getMessage(), false);
            return $this->json([
                $response->getSlackResponse()
            ], $exception->getCode());

        }

    }

}
