<?php

namespace IESElCaminas;


use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Swift_TransportException;
class MailServer
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;

    private $config;

    private $urlGitHub;
    /**
     * MyMail constructor.
     */
    public function __construct(array $config, string $mail, string $password)
    {
        $this->config = $config;
        $this->urlGitHub = "https://education.github.com/student/verify?school_id=" . $this->config['school_id'] .  "&student_id=NIA&signature=HASH";

        $transport = (new Swift_SmtpTransport(
            $this->config['smtp_server'],
            $this->config['smtp_port'],
            $this->config['smtp_security']
            ))
            ->setUsername($mail)
            ->setPassword($password);
        // Create the Mailer using your created Transport
        $this->mailer = new Swift_Mailer($transport);
    }

    /**
     * @param string $asunto
     * @param string $mailTo
     * @param string $nameTo
     * @param string $text
     */
    public function send(string $asunto, string $mailTo, string $nameTo, string $text, string $from) 
    {
        // Create a message
        $message = (new Swift_Message($asunto))
            ->setFrom([$from => $this->config['name']])
            ->setTo([$mailTo => $nameTo])
            ->setBody($text, 'text/html');

        // Send the message; 
        $result = $this->mailer->send($message);
        
        return $result;
    }

    private function getEmailBody(): string{
        return $this->config['email_body'];
    }

    /**
     *  Crea el cuerpo del correo de bienvenida con el enlace para activar la cuenta del alumno
     */
    public function getUserEmailBody(string $NIA, string $hash): string{
        $urlUserGithub =  str_replace("NIA", $NIA, $this->urlGitHub);
        $urlUserGithub =  str_replace("HASH", $hash, $urlUserGithub);
        return str_replace("hrefGithub", $urlUserGithub, $this->getEmailBody());
    }
}