<?php

namespace App\EventListener;

use App\Entity\Cita;
use App\Repository\CitaRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\Security\Core\Security;


class CalendarListener
{
    private $citaRepository;
    private $router;
    private $Auth;

    public function __construct(
        CitaRepository $citaRepository,
        UrlGeneratorInterface $router,
        Security $AuthUser
    ) {
        $this->citaRepository = $citaRepository;
        $this->router = $router;
        $this->Auth = $AuthUser;
    }

    public function load(CalendarEvent $calendar): void
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();
        // Modify the query to fit to your entity and needs
        // Change booking.beginAt by your start date property
        if($filters['expediente'] > 0){
            $citas = $this->citaRepository
            ->createQueryBuilder('cita')
            ->where('cita.fechaReservacion BETWEEN :start and :end and cita.expediente = :expe')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->setParameter('expe', $filters['expediente'])
            ->getQuery()
            ->getResult();
        }elseif($filters["expediente"] == 0){
            $citas = $this->citaRepository->createQueryBuilder('c')
            ->innerJoin('c.expediente', 'exp')
            ->innerJoin('exp.usuario', 'u')
            ->innerJoin('u.clinica', 'cli')
            ->where('c.fechaReservacion BETWEEN :inicio AND :end AND cli.id =:cl AND c.usuario=:doctor')
            ->setParameter('inicio', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->setParameter('cl', $this->Auth->getUser()->getClinica()->getId())
            ->setParameter('doctor', $this->Auth->getUser()->getId())
            ->getQuery()
            ->getResult();
            ;
        }else{
            
        }
        

        foreach ($citas as $cita) {
            // this create the events with your data (here booking data) to fill calendar
            $citaEvent = new Event(
                $cita->getExpediente()->getUsuario()->getPersona()->getPrimerNombre()." ".$cita->getExpediente()->getUsuario()->getPersona()->getPrimerApellido(),
                $cita->getFechaReservacion(),
                $cita->getFechaFin() // If the end date is null or not defined, a all day event is created.
            );

            /*
             * Add custom options to events
             *
             * For more information see: https://fullcalendar.io/docs/event-object
             * and: https://github.com/fullcalendar/fullcalendar/blob/master/src/core/options.ts
             */

            $citaEvent->setOptions([
                'backgroundColor'   => 'black',
                'color'             => 'white',
            ]);
            $citaEvent->addOption(
                'url',
                $this->router->generate('cita_show', [
                    'expediente' => $cita->getExpediente()->getId(),
                    'id' => $cita->getId(),
                ])
            );
            // finally, add the event to the CalendarEvent to fill the calendar
            $calendar->addEvent($citaEvent);
        }
    }
}