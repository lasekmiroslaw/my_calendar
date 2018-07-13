<?php

namespace AppBundle\Entity;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Security\Core\User\UserInterface;

class FOSUBUserProvider extends BaseClass
{

    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        $property = $this->getProperty($response);
        $username = $response->getUsername();

        // On connect, retrieve the access token and the user id
        $service = $response->getResourceOwner()->getName();

        $setter = 'set' . ucfirst($service);
        $setter_id = $setter . 'Id';
        $setter_token = $setter . 'AccessToken';

        // Disconnect previously connected users
        if (null !== $previousUser = $this->userManager->findUserBy(array($property => $username))) {
            $previousUser->$setter_id(null);
            $previousUser->$setter_token(null);
            $this->userManager->updateUser($previousUser);
        }

        // Connect using the current user
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());
        $this->userManager->updateUser($user);
    }

    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $username = $response->getUsername();
        $email = $response->getEmail();

        $user = $this->userManager->findUserByEmail($email);
        $service = $response->getResourceOwner()->getName();
        //when the user is registrating
        if (null === $user) {
            $setter = 'set'.ucfirst($service);
            $setter_id = $setter.'Id';
            $setter_token = $setter.'AccessToken';

            $user = $this->userManager->createUser();
            $user->$setter_id($username);
            $user->$setter_token($response->getAccessToken());

            $user->setUsername($this->generateRandomUsername($username, $response->getResourceOwner()->getName()));
            $user->setEmail($email);
            $user->setPassword(sha1(uniqid()));
            $user->setEnabled(true);
            $this->userManager->updateUser($user);

            return $user;
        }
        //if user exists - go with the HWIOAuth way
        if ($username !== $user->getUsername()) {
            $setter = 'set'.ucfirst($service);
            $setter_id = $setter.'Id';
            $user->$setter_id($username);
        }
        //update the accesss token
        $setter_token = 'set' . ucfirst($service) . 'AccessToken';
        $user->$setter_token($response->getAccessToken());

        return $user;
    }

    /**
     * Generates a random username with the given
     * e.g 12345_github, 12345_facebook
     *
     * @param string $username
     * @param type $serviceName
     * @return type
     */
    private function generateRandomUsername($username, $serviceName)
    {
        if (!$username) {
            $username = "user". uniqid((rand()), true) . $serviceName;
        }

        return $username. "_" . $serviceName;
    }
}
