<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\User;

class userController extends FOSRestController
{
    /**
     * @Rest\Get("/user")
     */
    public function getUsers()
    {
        $restresult = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();
        if ($restresult === null)
            return new View(['message' => 'There are no users yet', 'error' => 'true'], Response::HTTP_NOT_FOUND);
        
        return $restresult;
    }

    /**
     * @Rest\Get("/user/{id}")
     */
    public function getSingleUser($id)
    {
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        if ($singleresult === null)
            return new View(['message' => 'User not found', 'error' => 'true'], Response::HTTP_NOT_FOUND);

        return $singleresult;
    }

    /**
     * @Rest\Post("/user")
     */
    public function postUser(Request $request)
    {
        $data = new User;
        $name = $request->get('name');
        $role = $request->get('role');
        if(empty($name) || empty($role))
            return new View(['message' => 'Null values are not allowed', 'error' => 'true'], Response::HTTP_NOT_ACCEPTABLE);

        $data->setName($name);
        $data->setRole($role);
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();
        $last_id = $data->getId();
        $response = ['id' => $last_id, 'error' => 'false'];
        return new View($response, Response::HTTP_OK);
    }

    /**
     * @Rest\Put("/user/{id}")
     */
    public function updateUser($id,Request $request)
    {
        $name = $request->get('name');
        $role = $request->get('role');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);

        if (empty($user))
            return new View(['message' => 'User not found', 'error' => 'true'], Response::HTTP_NOT_FOUND);

        if (empty($name) && empty($role))
            return new View(['message' => 'Null values are not allowed', 'error' => 'true'], Response::HTTP_NOT_ACCEPTABLE);

        if (!empty($name))
            $user->setName($name);

        if (!empty($role))
            $user->setRole($role);

        $em->flush();
        $last_id = $user->getId();
        $response = ['id' => $last_id, 'error' => 'false'];
        return new View($response, Response::HTTP_OK);
    }

    /**
     * @Rest\Delete("/user/{id}")
     */
    public function deleteUser($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        if (empty($user))
            return new View(['message' => 'User not found', 'error' => 'true'], Response::HTTP_NOT_FOUND);

        $em->remove($user);
        $em->flush();
        $last_id = $user->getId();
        $response = ['id' => $last_id, 'error' => 'false'];
        return new View($response, Response::HTTP_OK);
    }
}