<?php

namespace App\Controller;

use App\Entity\Etiqueta;
use App\Entity\Marcador;
use App\Entity\MarcadorEtiqueta;
use App\Form\EtiquetaType;
use App\Form\MarcadorType;
use App\Repository\MarcadorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/marcador')]
class MarcadorController extends AbstractController
{
    #[Route('/', name: 'marcador_index', methods: ['GET'])]
    public function index(MarcadorRepository $marcadorRepository): Response
    {
        return $this->render('marcador/index.html.twig', [
            'marcadors' => $marcadorRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'marcador_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $marcador = new Marcador();
        $form = $this->createForm(MarcadorType::class, $marcador);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($marcador);
            $etiquetas = $form->get('etiquetas') -> getData(); // con esto se supone que tenemos todas las etiquetas mandadas ne el submit


            foreach($etiquetas as $etiqueta){
                $marcadorEtiqueta = new MarcadorEtiqueta();
                $marcadorEtiqueta->setMarcador($marcador);
                $marcadorEtiqueta->setEtiqueta($etiqueta);
                $entityManager->persist($marcadorEtiqueta); //persistimos la nueva entidad
            }


            $entityManager->flush();

            $this->addFlash('success' , "Marcador creador correctamente");

            return $this->redirectToRoute('app_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('marcador/new.html.twig', [
            'marcador' => $marcador,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'marcador_show', methods: ['GET'])]
    public function show(Marcador $marcador): Response
    {
        return $this->render('marcador/show.html.twig', [
            'marcador' => $marcador,
        ]);
    }

    #[Route('/{id}/edit', name: 'marcador_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Marcador $marcador): Response
    {
        $form = $this->createForm(MarcadorType::class, $marcador);
        $form->handleRequest($request);


        //esto no se porque no lo tengo pero lo copio del tio ahora mismo

        // $etiquetum = new Etiqueta();
        // $formEtiqueta = $this->createForm(EtiquetaType::class , $etiquetum ,[
            // 'action' => $this->generateUrl('nueva_etiqueta_ajax')
        // ]);

        //hasta aqui es donde no tenia pero ya si que lo tengo


        $marcadorEtiquetasActuales = $marcador->getMarcadorEtiquetas();


        if ($form->isSubmitted()) {

            if($form->isValid()){
                $entityManager = $this->getDoctrine()->getManager();
                $this->addFlash('success' , "Marcador editado correctamente");
                return $this->redirectToRoute('app_index', [], Response::HTTP_SEE_OTHER);
    
            }else{
                //TODO : QUEDA POR AHCER ESTO A VER
                $etiquetas = [];
                foreach($marcadorEtiquetasActuales as $marcadorEtiqueta){
                    $etiquetas[] = $marcadorEtiqueta->getEtiqueta(); //asignamos el valor de la etiqueta al array de etiquetas
                }

                $form->get('etiquetas') -> setData($etiquetas);// recuperamos el array de etiquetas y le pasamos un array con todas las etiquetas que tiene que pintar
            }
          
        }



        return $this->renderForm('marcador/edit.html.twig', [
            'marcador' => $marcador,
            'form' => $form,
            // 'form_etiqueta' => $formEtiqueta -> createView(), //esto no se porque no lo tenia puesto antes pero hay que ponerlo en el curso visto no se que punto me salte
        ]);
    }

    #[Route('/{id}', name: 'marcador_delete', methods: ['POST'])]
    public function delete(Request $request, Marcador $marcador): Response
    {
        if ($this->isCsrfTokenValid('delete'.$marcador->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($marcador);
            $entityManager->flush();
            $this->addFlash('success' , "Marcador eliminado correctamente");

        }

        return $this->redirectToRoute('app_index', [], Response::HTTP_SEE_OTHER);
    }
}
