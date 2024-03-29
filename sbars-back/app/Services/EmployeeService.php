<?php

namespace App\Services;
use App\Repositories\EmployeeRepository;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class EmployeeService
{
    protected EmployeeRepository $repository;

    public function __construct(EmployeeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function listar($porPagina = 5, $direcao = null, $ordenarPor = null, $criterioBusca = null, $filtros = null): JsonResponse
    {
        $retorno = $this->repository->listar($porPagina, $direcao, $ordenarPor, $criterioBusca, $filtros);
        return response()->json($retorno, Response::HTTP_OK);
    }

    public function cadastrar(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), (new StoreEmployeeRequest)->rules(), (new StoreEmployeeRequest)->messages());
        if ($validator->fails()) {
            return response()->json([
                'dados' => null,
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $retorno = $this->repository->cadastrar($request);

        if ($retorno === true) {
            return response()->json([
                'data' => true,
                'message' => 'Registro cadastrado com sucesso.',
                'notification' => null,
            ], Response::HTTP_CREATED);
        }

        return response()->json([
            'data' => $retorno,
            'notification' => null,
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function editar($id, Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), (new UpdateEmployeeRequest)->rules($id));

        if ($validator->fails()) {
            return response()->json([
                'dados' => null,
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $model = $this->repository->editar($id, $request);

        if (is_object($model)) {
            return response()->json([
                'data' => true,
                'message' => 'Registro atualizado com sucesso.',
                'notification' => null,
            ], Response::HTTP_OK);
        }

        return response()->json([
            'data' => null,
            'notification' => null,
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function buscarPor($column, $value): JsonResponse
    {
        $retorno = $this->repository->buscarPor($column, $value, true);

        if (is_string($retorno)) {
            return response()->json([
                'data' => null,
                'notification' => $retorno,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'data' => $retorno,
            'notification' => null,
        ], Response::HTTP_OK);
    }

    public function excluir(string $id): JsonResponse
    {
        $retorno = $this->repository->excluir($id);

        if ($retorno == true) {
            return response()->json([
                'data' => null,
                'notification' => null,
                'message' => 'Registro excluído com sucesso.'
            ], Response::HTTP_ACCEPTED);
        }

        return response()->json([
            'data' => null,
            'notification' => $retorno,
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function options(): JsonResponse
    {
        $retorno = $this->repository->options(100);
        return response()->json($retorno, Response::HTTP_OK);
    }
}
