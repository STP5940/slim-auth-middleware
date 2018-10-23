<?php
/**
 * Created by PhpStorm.
 * User: Marvin Petker
 * Date: 23.10.2018
 * Time: 10:27
 */

namespace Crtl\Authorization;


class BasicAuthorization extends AbstractAuthorization
{

    const CONFIG_ENABLE = "enable";
    const CONFIG_USER = "user";
    const CONFIG_SECRET = "secret";

    /**
     * @throws \RuntimeException
     * @return bool
     */
    protected function isAuthorized()
    {
        if (!($this->config[self::CONFIG_ENABLE] ?? false)) {
            return true;
        }

        list($type, $credentials) = explode(" ", $this->request->getHeaderLine("Authorization"));

        if (!$this->config[self::CONFIG_USER] || !$this->config[self::CONFIG_SECRET]) {
            throw new \RuntimeException(
                sprintf("Authorization is not configured properly. Please supply config: %s and %s", self::CONFIG_USER, self::CONFIG_SECRET)
            );
        }

        if (!$credentials || $type !== "Basic") {
            return false;
        }

        list($user, $secret) = explode(":", base64_decode($credentials));


        return $user === $this->config[self::CONFIG_USER] && $secret === $this->config[self::CONFIG_SECRET];
    }


}