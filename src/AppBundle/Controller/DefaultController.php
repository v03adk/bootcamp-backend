<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Site;
use AppBundle\Form\SiteType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $site = new Site();

        $form = $this->createForm(SiteType::class, $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $apiKey = Uuid::uuid4()->toString();
            $site->setApiKey($apiKey);

            $em = $this->getDoctrine()->getManager();

            try {
                $em->persist($site);
                $em->flush();
            } catch (UniqueConstraintViolationException $e) {
                $site = $this->getDoctrine()->getRepository(Site::class)->findOneBy(['name' => $site->getName()]);
            }

            return $this->render('default/site_created.html.twig', [
                'site' => $site,
            ]);
        }

        return $this->render('default/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
