<?php

namespace App\Controller;

use App\Entity\Oeuvre;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{


    #[Route('/cart', name: 'app_cart')]
    public function index(SessionInterface $session, ManagerRegistry $doctrine): Response
    {
        #on récupère la session 'panier' si elle existe - sinon elle est créée avec un tableau vide
        $panier = $session->get('panier', []);

        #variable tableau
        $panierData = [];

        #On boucle sur la session 'panier' pour recuperer proprement l'objet au lieu de l'id et la quantité
        foreach ($panier as $id => $quantity) {
            $panierData[] = [
                "product" => $doctrine->getRepository(Oeuvre::class)->find($id),
                "quantity" => $quantity
            ];
        }


        #On calcule le total du panier ici
        $total = 0;
        if ($panierData !== null) {
        foreach ($panierData as $id => $value) {
            if ($value['product'] !== null) {
                $total += $value['product']->getPrix() * $value['quantity'];
            }
        }
        }
        // dump($total);
        // die;

        return $this->render('cart/index.html.twig', [
            "items" => $panierData,
            "total" => $total

        ]);
    }

    /**
     * @Route("/cart/add/{id}", name="cart_add")
     */
    public function cartAdd($id, SessionInterface $session)
    {
        #on récupère la session 'panier' si elle existe - sinon elle est créée avec un tableau vide
        $panier = $session->get('panier', []);
        #Etape 2 : On ajoute la quantité 1, au produit d'id $id

        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }

        #Etape 3
        $session->set('panier', $panier);

        //dd($session->get('panier', []));

        return $this->redirectToRoute('home');
    }





    /**
     * @Route("/panier/delete/{id}", name="cart_delete")
     */
    public function delete($id, SessionInterface $session)
    {
        #On récupere la session 'panier' si elle existe - sinon elle est créée avec un tableau vide
        $panier = $session->get('panier', []);

        #On supprime de la session celui dont on a passé l'id
        if (!empty($panier[$id])) {
            $panier[$id]--;

            if ($panier[$id] <= 0) {
                unset($panier[$id]); //unset pour dépiler de la session
            }
        }

        #On réaffecte le nouveau panier à la session
        $session->set('panier', $panier);

        #On redirige vers le panier
        return $this->redirectToRoute('app_cart');
    }



    /**
     * @Route("/panier/clear", name="cart_clear")
     */
    public function clearCart(SessionInterface $session)
    {
        #On vide le panier
        $session->remove('panier');

        #On redirige vers le panier
        return $this->redirectToRoute('app_cart');
    }
}
