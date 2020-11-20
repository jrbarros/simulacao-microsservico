<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\SensitiveInformation;
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
    use ValidateEmptyContentTrait;
    use RequestTrait;

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

            $this->isEmpty($content);

            $data = $this->contentToArray($content);

            $this->sensitiveInformationService->processSave($data);

            return $this->json(['message' => SensitiveInformationMessage::CREATE_RESPONSE]);
        } catch (\Exception $exception) {
            return $this->json(
                [
                    'message' => SensitiveInformationExceptionMessage::DEFAULT_ERROR_MESSAGE,
                    'error' => $exception->getMessage(),
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @Route("/{id}", name="update", methods={"PUT"})
     *
     * @param Request $request
     * @param $id
     *
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $sensitiveInformation = $this->sensitiveInformationService->findSensitiveInformationById($id);

            if (!$sensitiveInformation instanceof SensitiveInformation) {
                throw new \RuntimeException(SensitiveInformationExceptionMessage::SENSITIVE_INFORMATION_NOT_EXIST);
            }

            $content = $request->getContent();

            $this->isEmpty($content);

            $data = $this->contentToArray($content);

            $this->sensitiveInformationService->processUpdate($data, $sensitiveInformation);

            return $this->json(['message' => SensitiveInformationMessage::UPDATE_RESPONSE]);
        } catch (\Exception $exception) {
            return $this->json(
                [
                    'message' => SensitiveInformationExceptionMessage::DEFAULT_ERROR_MESSAGE,
                    'error' => $exception->getMessage(),
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @Route("/{id}", name="find_by_id", methods={"GET"})
     *
     * @param $id
     *
     * @return JsonResponse
     */
    public function find($id): JsonResponse
    {
        try {
            $sensitiveInformation = $this->sensitiveInformationService->findSensitiveInformationById($id);

            if (!$sensitiveInformation instanceof SensitiveInformation) {
                throw new \RuntimeException(SensitiveInformationExceptionMessage::SENSITIVE_INFORMATION_NOT_EXIST);
            }

            $dataReturn = $this->buildSensitiveInformationDataReturn($sensitiveInformation);

            return $this->json(
                [
                    'message' => SensitiveInformationMessage::GET_RESPONSE,
                    'data'    => $dataReturn
                ]
            );
        } catch (\Exception $exception) {
            return $this->json(
                [
                    'message' => SensitiveInformationExceptionMessage::DEFAULT_ERROR_MESSAGE,
                    'error' => $exception->getMessage(),
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
