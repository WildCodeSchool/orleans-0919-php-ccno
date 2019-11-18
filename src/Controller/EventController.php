<?php


namespace App\Controller;

use App\Model\EventManager;

class EventController extends AbstractController
{
    public function index($month, $year)
    {
        $eventManager = new EventManager();
        $events = $eventManager->showEventPerMonth($month, $year);
        $globalevents = $eventManager->showEvent();
        setlocale(LC_TIME, "fr_FR");
        $monthName = strftime('%B', mktime(0, 0, 0, $month, 10));
        $currentDateMY = ['month' => date("n"), 'year' => date("Y")];
        $isSetDate = [];
        foreach ($globalevents as $event) {
            $isSetDate[] = $event['month'] . "/" . $event['year'];
        }

        $isSetDate = array_merge(array_unique($isSetDate));

        $dateKey = $this->currentDateKey($isSetDate, $month, $year);

        if ($dateKey > 0 && $dateKey < count($isSetDate) - 1) {
            $previousDate = $isSetDate[$dateKey - 1];
            $nextDate = $isSetDate[$dateKey + 1];
        } elseif ($dateKey > 0 && $dateKey == count($isSetDate) - 1) {
            $previousDate = $isSetDate[$dateKey - 1];
            $nextDate = $isSetDate[$dateKey];
        } elseif ($dateKey == 0 && $dateKey < count($isSetDate) - 1) {
            $previousDate = $isSetDate[$dateKey];
            $nextDate = $isSetDate[$dateKey + 1];
        }

        return $this->twig->render('Event/index.html.twig', [
            'events' => $events,
            'globalevents' => $globalevents,
            'monthName' => $monthName,
            'year' => $year,
            'currentDateMY' => $currentDateMY,
            'previousDate' => $previousDate ?? $isSetDate,
            'nextDate' => $nextDate ?? $isSetDate,
        ]);
    }

    public function detail($id)
    {
        $eventManager = new EventManager();
        $detail = $eventManager->selectEventById($id);
        return $this->twig->render('Event/detail.html.twig', [
            'detail' => $detail
        ]);
    }

    private function currentDateKey($isSetDate, $month, $year)
    {
        foreach ($isSetDate as $key => $date) {
            if ($date === "$month/$year") {
                return $key;
            }
        }
    }
}
