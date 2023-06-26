<?php

namespace App\Controller;

use App\Entity\Oeuvre;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentController extends AbstractController
{
  #[Route('/payment', name: 'app_payment')]
  public function index(): Response
  {
    return $this->render('payment/index.html.twig', [
      'controller_name' => 'PaymentController',
    ]);
  }

  /**
   * @Route("/checkout", name="payment_checkout")
   */
  public function checkout($stripeSK, SessionInterface $session, ManagerRegistry $doctrine)
  {
    //$stripe = new \Stripe\StripeClient('sk_test_51NBHfWHRXxl48zKyV5Qr5pYvGUxQ8pRqbxFqqb9kwivS5x5CZzyPFXr7NF6YSEH0vJYUKyZtBiisi17dHEASWfgC00OAY5cP4H');

    Stripe::setApiKey('sk_test_51NBHfWHRXxl48zKyV5Qr5pYvGUxQ8pRqbxFqqb9kwivS5x5CZzyPFXr7NF6YSEH0vJYUKyZtBiisi17dHEASWfgC00OAY5cP4H');

    $panier = $session->get('panier', []);

    $panierData = [];
    foreach ($panier as $id => $quantity) {
      #On enrichi le tableau avec l'objet (qui contient toutes les informations du produit) + la quantité
      $panierData[] = [
        "product" => $doctrine->getRepository(Oeuvre::class)->find($id),
        "quantity" => $quantity
      ];
    }

    foreach ($panierData as $id => $value) {
      if ($value['product'] !== null) {
        $line_items[] = [
          'price_data' => [
            'currency' => 'eur',
            'product_data' => [
              'name' => $value['product']->getTitre(),
            ],
            'unit_amount' => $value['product']->getPrix() * 100, //Attention: bien mettre le format sans virgule et collé avec les centimes => dans notre cas, le prix est un entier donc ici on multiplie simplement par 100 (exemple 20€ donne 2000)
          ],
          'quantity' => $value['quantity'],
        ];
      }
    }

    $session = Session::create([
      'line_items' => [
        $line_items //On place le tableau construit juste au-dessus, pour les line_items.
      ],
      'mode' => 'payment',
      'success_url' => $this->generateUrl('success_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
      'cancel_url' => $this->generateUrl('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
    ]);

    //dd($session);
    return $this->redirect($session->url, 303);
  }


  /**
   * @Route("/payment/success", name="success_url")
   */
  public function successUrl(SessionInterface $session)
  {
    $session->remove('panier');

    return $this->render("payment/success.html.twig");
  }

  /**
   * @Route("/payment/cancel", name="cancel_url")
   */
  public function cancelUrl()
  {
    return $this->render("payment/cancel.html.twig");
  }
}
