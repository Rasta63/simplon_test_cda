<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\DBAL\DBALException;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/users", name="list_users", methods={"GET"})
     */
    public function listUsers(UserRepository $userRepository,SerializerInterface $serializer)
    {
        //permet de recuperer tous les utilisateur de la base de données
        $users = $userRepository->findAll();
        //Sérialisation des données pour les retournée au forma json pour qu'elles soient exploitables par differents applications(web, mobile etc )
        $serializedUsers = $serializer->serialize($users, 'json', ['groups' => ['user']]);
        return new JsonResponse($serializedUsers, 200, [], true);
    }

    /**
     * @Route("/users/{id}", name="show_user", methods={"GET"})
     */
    public function showUser(int $id,UserRepository $userRepository,SerializerInterface $serializer)
    {
        //bloque qui permet de lever et attraper les erreurs
        try {
            //recuperation de l'utilisateur dans la base de données à l'aide de son identiffiant
            $user = $userRepository->find($id);
            if (! $user instanceof User){
                //erreur levé si on ne trouve pas l'utilisateur demander
                throw new DBALException("User not found",404);
            }
            $serializedUsers = $serializer->serialize($user, 'json', ['groups' => ['user']]);
            return new JsonResponse($serializedUsers, 200, [], true);

        }catch(DBALException $e){
            return $this->json([
                'status' => $e->getCode(),
                'message' => $e->getMessage()
            ],$e->getCode());
        }
    }

     /**
     * @Route("/users/{id}", name="delete_user", methods={"DELETE"})
     */
    public function deleteUser(int $id,UserRepository $userRepository,EntityManagerInterface $entityManager)
    {
        try {
            //
             /** @var user */
             $user = $userRepository->find($id);

             if (!$user instanceof user){
                 throw new DBALException("User not found",404);
             }
             $entityManager->remove($user);
             $entityManager->flush();

             return $this->json(["message"=>"User delete"],200);

        }catch(DBALException $e){
            return $this->json([
                'status' => $e->getCode(),
                'message' => $e->getMessage()
            ],$e->getCode());
        }  
        
    }

     /**
     * @Route("/users/{id}", name="update_use", methods={"PUT"})
     */
    public function updateUser( $id,UserRepository $userRepository, Request $request,SerializerInterface $serializer,EntityManagerInterface $entityManager,ValidatorInterface $validator)
    {
        try{

            $user = $userRepository->find($id);
            if (!$user instanceof User){
                throw new DBALException("User not found",404);
            }
            //recuperation des donnes contenu dans le corps de la requette envoyé avec la methode PUT au forma json
            $jsonData = $request->getContent();
       
            //déserialisation des données reçu en specifiant que c'est un utilisateur 
            //pour la modification de user recuperé en base de données
            /** @var User */
            $userUpdate = $serializer->deserialize($jsonData,User::class,'json');
            
            //Ses conditions permet de mettre à jout que les éléments qui sont modifiés
            if ($userUpdate->getEmail() != null) {
                $user->setEmail($userUpdate->getEmail());
            }
            if ($userUpdate->getPassword() != null) {
                //securisation du mots de passe avec l'agorithme 'sha256'
                $user->setPassword(hash('sha256',$userUpdate->getPassword()));
            }
            if ($userUpdate->getBirthDate() != null) {
                $user->setBirthDate($userUpdate->getBirthDate());
            }

            //Une erreur est levé si les éléments modifiés ne correspondents pas aux éléments d'un utilisateur(email,date de naissance)
            $errors = $validator->validate($user);
           if (count($errors) > 0) {
               throw new NotEncodableValueException($errors->message,400);
           }
           //Prise en compte des modifications dans la base de données
            $entityManager->flush(); 

            $serializedUsers = $serializer->serialize($user, 'json', ['groups' => ['user']]);
            return new JsonResponse($serializedUsers, 200, [], true);

        }catch(NotEncodableValueException $e){
            return $this->json([
                'status' => $e->getCode(),
                'message' => $e->getMessage()
            ],$e->getCode());
        }catch(DBALException $e){
            return $this->json([
                'status' => $e->getCode(),
                'message' => $e->getMessage()
            ],$e->getCode());
        }
    }

    /**
     * @Route("/users", name="add_user", methods={"POST"})
     */
    public function addUser(Request $request,SerializerInterface $serializer,EntityManagerInterface $entityManager,ValidatorInterface $validator,UserRepository $userRepository)
    {
        try{
  
            $jsonData = $request->getContent();
       
            /** @var User */
            $user = $serializer->deserialize($jsonData,User::class,'json');
            $user->setPassword(hash("sha256",$user->getPassword()));
            $errors = $validator->validate($user);
           if (count($errors) > 0) {
              throw new NotEncodableValueException($errors->message, 400);
              
           }
           $entityManager->persist($user);
           $entityManager->flush();
           $serializedUsers = $serializer->serialize($user, 'json', ['groups' => ['user']]);
            return new JsonResponse($serializedUsers, 200, [], true);

        }catch(NotEncodableValueException $e){
            return $this->json([
                'status' => $e->getCode(),
                'message' => $e->getMessage()
            ],$e->getCode());
        }catch(AccessDeniedException $e){
            return $this->json([
                'status' => 403,
                'message' => "Access deniede"
            ],400);
        }
    }

}

