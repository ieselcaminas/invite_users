<?php 
namespace IESElCaminas;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use IESElCaminas\MailServer;

/**
 * Author: Chidume Nnamdi <kurtwanger40@gmail.com>
 * https://codeburst.io/build-a-php-console-application-using-symfony-692a876f416
 */
class Command extends SymfonyCommand
{

    public function __construct()
    {
        parent::__construct();
    }
    protected function getCredentials(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output -> writeln([
            "Introduïu les credencials per al compte de correu de l'IES en GitHub for Education",
            '',
        ]);
        
        // outputs a message without adding a "\n" at the end of the line
        //$output -> write($this -> getGreeting());
        $helper = $this->getHelper('question');
        $question = new Question('Email: ', '');
        $this->email = $helper->ask($input, $output, $question);
        $question = new Question('Contraseña: ');
        $question->setHidden(true);
        $question->setHiddenFallback(false);
    
        $this->password = $helper->ask($input, $output, $question);
        
    }

}