<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\{Model, Collection};
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Config;

class BasicTenantController extends Controller
{

    public $order;
    protected $auth;

    public function __construct()
    {
        parent::__construct();
        $this->changeTimeZone();
        $this->auth = auth()->user();
    }

    public function authUser(): ?object
    {
        return $this->auth;
    }

    public function authUserId(): ?string
    {
        return $this->auth?->id;
    }

    public function changeTimeZone()
    {
        if (tenant()->timezone) {
            Config::set('app.timezone', tenant()->timezone);
            date_default_timezone_set(tenant()->timezone);
        }
    }

    public function getFile(string $path)
    {
        $tenant = tenant();
        if (!$tenant)
            return false;
        $hasDot = str_contains($path, '.');
        if ($hasDot) {
            $arquivo = storage_path("app/public/anexos/{$path}");
        } else {
            try {
                $file = $this->findOrFail(new Anexo(), $path);
                $arquivo = storage_path("app/public/anexos/{$file->arquivo}");
            } catch (ModelNotFoundException) {
                $arquivo = false;
            }
        }
        if (file_exists($arquivo)) {
            return $arquivo;
        }
        return false;
    }

    public function getFileURL(string $file)
    {
        if ($this->getFile($file)) {
            $rota = env('ROUTE_ANEXO_NAME');
            return "api/{$rota}/{$file}";
        }
        return "";
    }

    public function findOrFail(Model $model, string $id, bool $trash = false, array $relation = []): Model|bool
    {
        try {
            $query = $model->newQuery();
            if (isset($relation) && count($relation) > 0) {
                //dd($relation);
                $query->with($relation);
            }
            if ($trash && method_exists($model, 'trashed')) {
                $query->withTrashed();
            }
            return $query->findOrFail($id);
        } catch (ModelNotFoundException) {
            return false;
        }
    }

    public function whereOrFail(Model $model, string|array $column, string|array $value, bool $trash = false, array $relation = [], array $append = []): Collection|bool
    {
        try {
            $query = $model->newQuery();
            if (isset($relation) && count($relation) > 0) {
                //dd($relation);
                $query->with($relation);
            }
            if ($trash && method_exists($model, 'trashed')) {
                $query->withTrashed();
            }
            if (isset($this->order)) {
                $query->orderBy($this->order);
            }
            if (is_array($column)) {
                for ($i = 0; $i < count($column); $i++) {
                    $query->where($column[$i], $value[$i]);
                }
            } else {
                $query->where($column, $value);
            }
            $get = $query->get();
            if (isset($append) && count($append) > 0) {
                foreach ($get as $item) {
                    $item->append($append);
                }
            }
            return $get;
        } catch (ModelNotFoundException) {
            return false;
        }
    }
}
