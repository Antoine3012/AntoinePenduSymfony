<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Mot;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class JeuController extends AbstractController
{
    /**
     * @Route("/", name="jeu_index")
     */
    public function index(EntityManagerInterface $entityManager)
    {
        $motRepository = $entityManager->getRepository(Mot::class);
        $mot = $motRepository->findOneRandom();
        $motuse = $mot->getMot();
        $motuse = strtolower($motuse);

        $lettresDevinees = [];

        for($i=0; $i<strlen($motuse); $i++){
            $lettresDevinees[] = "_";
        }

        return $this->render('jeu/jeu.html.twig', [
            'mot' => $motuse,
            'lettresDevinees' => $lettresDevinees,
            'erreur' => 0
        ]);
    }

    /**
     * @Route("/deviner-lettre", name="jeu_deviner_lettre", methods={"POST"})
     */
    public function devinerLettre(Request $request, EntityManagerInterface $entityManager)
    {
        $lettre = $request->request->get('lettre');
        $motComplet = $request->request->get('mot');
        $motatrou = $request->request->get('motatrou');
        $erreur = $request->request->get('erreur');

        $lettresDansMot = str_split($motComplet);

        $all = $request->request->all();
        var_dump($all);


        $motatrou = str_split($motatrou);
        
        $index = 0;
        $lettreTrouvee = false;

        foreach ($lettresDansMot as $lettreDansMot) {
            if($lettre == $lettreDansMot){
                $motatrou[$index] = $lettre;
                $lettreTrouvee = true;
            }
            
            $index++;
        }

        if (!$lettreTrouvee) {
            $erreur++;
        }
        var_dump($motatrou);

        return $this->render('jeu/jeu.html.twig', [
            'mot' => $motComplet,
            'motatrou' => $motatrou,
            'lettresDevinees' => $motatrou,
            'erreur' => $erreur
        ]);
    }
}

