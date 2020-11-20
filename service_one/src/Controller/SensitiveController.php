<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\SensitiveInformationService;
use App\Validator\SensitiveInformationExceptionMessage;
use App\Validator\SensitiveInformationMessage;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SensitiveController.
 *
 * @Route("v1/sensitive-information", name="sensitive_information_")
 */
class SensitiveController extends AbstractController
{
    /**
     * @var ManagerRegistry
     */
    private ManagerRegistry $managerRegistry;

    /**
     * @var SensitiveInformationService
     */
    private SensitiveInformationService $sensitiveInformationService;

    /**
     * SensitiveController constructor.
     *
     * @param ManagerRegistry             $managerRegistry
     * @param SensitiveInformationService $sensitiveInformationService
     */
    public function __construct(
        ManagerRegistry $managerRegistry,
        SensitiveInformationService $sensitiveInformationService
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->sensitiveInformationService = $sensitiveInformationService;
    }

    /**
     * @Route("", name="create",methods={"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        try {
            $content = $request->getContent();

            if (empty($content)) {
                throw new \RuntimeException(SensitiveInformationExceptionMessage::EMPTY_BODY);
            }

            $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

            $this->sensitiveInformationService->processSave($data);
        } catch (\Exception $exception) {
            return $this->json(
                [
                    'message' => SensitiveInformationExceptionMessage::DEFAULT_ERROR_MESSAGE,
                    'error' => $exception->getMessage(),
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        return $this->json(['message' => SensitiveInformationMessage::CREATE_RESPONSE]);
    }
}
