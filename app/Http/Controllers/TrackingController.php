<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ActivityLog;
use App\Http\Resources\ActivityLogResource;
use App\Models\ActivityLog as ModelsActivityLog;

class TrackingController extends Controller
{
    protected $activityLogService;

    public function __construct(ActivityLog $activityLog)
    {
        $this->activityLogService = $activityLog;
    }

    public function getAlltracks(Request $request)
    {
        $this->activityLogService->createLog('Lister la journalisation');

        $limit = $request->input('limit', 5);
        $start = $request->query('start');
        $end = $request->query('end');

        // Vérifier si les paramètres de date sont présents
        if (!empty($start) || !empty($end)) {
            // Si oui, appeler la méthode pour récupérer les activités selon les dates spécifiées
            return $this->getTrafickByDate($request);
        }

        // Sinon, récupérer toutes les activités
      //  $activities = ModelsActivityLog::orderByDesc('id')->paginate($limit);
        $activities = ModelsActivityLog::all();

        return  ActivityLogResource::collection($activities);


        // Transformer les activités en ressource ActivityLogResource
        return ActivityLogResource::collection($activities->items())->additional([
            'pagination' => [
                'total' => $activities->total(),
                'per_page' => $activities->perPage(),
                'current_page' => $activities->currentPage(),
                'last_page' => $activities->lastPage(),
                'from' => $activities->firstItem(),
                'to' => $activities->lastItem(),
            ],
        ]);
    }



    public function getTrafickByDate(Request $request)
    {
        $start = $request->query('start');
        $end = $request->query('end');

        if (empty($start) || empty($end)) {
            return response()->json(['error' => 'Les paramètres de date sont manquants'], 400);
        }

        $startDate = new \DateTime($start);
        $endDate = new \DateTime($end);

        $activities = ModelsActivityLog::whereBetween('created_at', [$startDate, $endDate])
            ->orderByDesc('id')
            ->get();


        return ActivityLogResource::collection($activities);
    }
}
