<?php declare(strict_types=1);


namespace App\Controller;


use App\Entity\SensitiveInformation;
use App\Validator\CpfValidator;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;


/**
 * Class SensitiveController
 * @Route("v1/sensitive-information", name="sensitive_information_")
 * @package App\Controller
 */
class SensitiveController extends AbstractController
{

    /**
     * @var ManagerRegistry
     */
    private ManagerRegistry $managerRegistry;

    /**
     * SensitiveController constructor.
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @Route("", name="create",methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        try {

            $sensitiveInformation = new SensitiveInformation();

            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

            if(! array_key_exists('cpf', $data) || empty($data['cpf'])) {
               throw new \RuntimeException("Campo 'cpf' não enviado ou estar em branco");
            }

            if(! array_key_exists('name', $data) || empty($data['name'])) {
                throw new \RuntimeException("Campo 'name' não enviado ou estar em branco");
            }

            if(! array_key_exists('address', $data) || empty($data['address'])) {
                throw new \RuntimeException("Campo 'address' não enviado ou estar em branco");
            }

            $cpf = preg_replace('/\D/', '', $data['cpf']);

            if (CpfValidator::validateCpf($cpf) ===  false) {
                throw new \RuntimeException("Campo 'cpf' não é valido");
            }

            $sensitiveInformation->setCpf($cpf);
            $sensitiveInformation->setName($data['name']);
            $sensitiveInformation->setAddress($data['address']);

            $this->managerRegistry->getManager()->persist($sensitiveInformation);
            $this->managerRegistry->getManager()->flush();
        } catch (\Exception $exception) {
            return $this->json(
                [   'message' => '',
                    'error' => $exception->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        return $this->json(['message' => 'Informação gravada com sucesso!']);
    }
}
