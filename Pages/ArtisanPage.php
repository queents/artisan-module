<?php


namespace Modules\Artisan\Pages;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Artisan;
use Modules\Artisan\Pages\Traite\Methods;
use Modules\Base\Services\Components\Base\Render;
use Modules\Base\Services\Resource\Page;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\BufferedOutput;

class ArtisanPage extends Page
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, Methods;

    public ?string $path = "artisan";
    public ?string $group = "Tools";
    public ?string $icon = "bx bxs-terminal";

    public function index(){
        $commands = $this->prepareToJson(config('artisan.commands', []));

        return Render::make('Artisan')->module('Artisan')->data([
            "commands" => $commands,
            "roles" => [
                "view" => $this->canView,
                "viewAny" => $this->canViewAny,
                "edit" => $this->canEdit,
                "create" => $this->canCreate,
                "delete" => $this->canDelete,
                "deleteAny" => $this->canDeleteAny,
            ],
            "render" => [
                "components" => $this->components(),
                "lang" => $this->loadTranslations(),
            ],
            "list" => [
                "url" => $this->table
            ]
        ])->render();
    }
}
