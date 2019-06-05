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
    /**
     * MyMail constructor.
     */
    public function __construct(array $config, string $mail, string $password)
    {
        $this->config = $config;
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
            ->setBody($text);

        // Send the message; 
        $result = $this->mailer->send($message);
        
        return $result;
    }
}