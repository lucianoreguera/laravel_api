<?php

namespace App\Filters;

use Illuminate\Http\Request;

class ApiFilter
{
    // Parametros con los que queremos filtrar nuestros modelos
    protected $safeParams = [];
    // Mapea las columnas para el filtrado. Ej: postCode => post_code
    protected $columnMap = [];
    // Mapea los operadores. Ej eq => =
    protected $operatorMap = [];

    public function transform(Request $request)
    {
        $eloQuery = [];

        foreach ($this->safeParams as $param => $operators) {
            $query = $request->query($param);

            if (!isset($query)) {
                continue;
            }

            $column = $this->columnMap[$param] ?? $param;

            foreach ($operators as $operator) {
                if (isset($query[$operator])) {
                    $eloQuery[] = [$column, $this->operatorMap[$operator], $query[$operator]];
                }
            }
        }

        return $eloQuery;
    }
}