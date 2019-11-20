<?php


namespace App\Controller;

use App\Model\EventManager;

class EventController extends AbstractController
{
    public function index($month = '', $year = '')
    {
        $eventManager = new EventManager();
        $currentDateMY = $this->nextEvent($month, $year);

        if (!$month && !$year) {
            $month = $currentDateMY['month'];
            $year = $currentDateMY['year'];
        }


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

        $date = $this->findMonth($isSetDate, $dateKey);

        $previousDate = $date[0];
        $nextDate = $date[1];

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
        $represManager = new EventManager();
        $representations = $represManager->showRepresentations($id);
        return $this->twig->render('Event/detail.html.twig', [
            'detail' => $detail,
            'representations' => $representations,

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

    private function nextEvent($month, $year)
    {
        $eventManager = new EventManager();
        $currentDateMY = ['month' => date("n"), 'year' => date("Y")];
        if (!$month && !$year) {
            $globalEvents = $eventManager->showEvent();
            $isSetDate = [];
            foreach ($globalEvents as $event) {
                $isSetDate[] = $event['month'] . "/" . $event['year'];
            }

            while (!in_array($currentDateMY['month'] . '/' . $currentDateMY['year'], $isSetDate)) {
                if ($currentDateMY['month'] < 12) {
                    $currentDateMY['month'] += 1;
                } elseif ($currentDateMY['month'] === 12) {
                    $currentDateMY['month'] = 1;
                    $currentDateMY['year'] += 1;
                }
            }
            return $currentDateMY;
        }
    }

    private function findMonth($isSetDate, $dateKey)
    {
        if ($dateKey > 0 && $dateKey < count($isSetDate) - 1) {
            $previousDate = $isSetDate[$dateKey - 1];
            $nextDate = $isSetDate[$dateKey + 1];
        } elseif ($dateKey > 0 && $dateKey == count($isSetDate) - 1) {
            $previousDate = $isSetDate[$dateKey - 1];
            $nextDate = $isSetDate[$dateKey];
        } elseif ($dateKey == 0 && $dateKey < count($isSetDate) - 1) {
            $previousDate = $isSetDate[$dateKey];
            $nextDate = $isSetDate[$dateKey + 1];
        } else {
            $previousDate = $isSetDate[$dateKey];
            $nextDate = $isSetDate[$dateKey];
        }
        return [$previousDate, $nextDate];
    }
}
