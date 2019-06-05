<?php namespace IESElCaminas;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Swift_TransportException;
use IESElCaminas\MailServer;

use IESElCaminas\Command;

/**
 * Author: Chidume Nnamdi <kurtwanger40@gmail.com>
 */
class InviteUsersCommand extends Command
{
    protected $email;
    protected $password;
    protected $config;
    public function __construct(array $config)
    {
        parent::__construct();
        $this->config = $config;
    
    }
    public function configure()
    {
        $this -> setName('send')
            -> setDescription("Enviar correu d'invitació a GitHub for Education");
            //-> addArgument('username', InputArgument::REQUIRED, 'The username of the user.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this -> getCredentials($input, $output);
        try{
            $mail = new MailServer($this->config, $this->email, $this->password);
            $mail->send("sdsf", "victorponz@gmail.com", "" , "hola", $this->email, $this->password);
        }catch (Swift_TransportException $swift_TransportException){
            $message = "S'ha produít un error en connectar\n";
            $message .= "Comproveu l'usuari i contrasenya\n";
            $message .= "Assegureu-vos que heu permés al compte de correu $this->email l'accés 'lesssecureapps'\n";
            $message .= "Més informació en https://myaccount.google.com/lesssecureapps";
            $output->writeln("<error>$message</error>");
            //echo $swift_TransportException->getCode();// . " " . $swift_TransportException->getMessage();
        }

    }
}