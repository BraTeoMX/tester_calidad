@extends('layouts.app', ['pageSlug' => 'dashboardPorDia', 'titlePage' => __('Dashboard')])

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-success card-header-icon">
                    <h2 class="card-title" style="text-align: center; font-weight: bold;">Dashboard Consulta por dia Planta 1 - Ixtlahuaca </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="fecha_inicio">Fecha de inicio</label>
                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="{{ $fechaActual->format('Y-m-d') }}" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label ></label>
                <button type="submit" class="btn btn-secondary">Mostrar datos</button>
            </div>
        </div>
    </div>

    <style>
        .custom-body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #ffffff;
            margin: 0;
            padding: 20px;
        }
        .custom-card {
            background-color: #1e1e1e;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .custom-card-header {
            background-color: #2e7d32;
            color: white;
            padding: 15px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .custom-card-body {
            padding: 15px;
        }
        .custom-table {
            width: 100%;
            border-collapse: collapse;
        }
        .custom-table th, .custom-table td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #333;
        }
        .custom-table th {
            background-color: #2e2e2e;
        }
        .custom-btn {
            background-color: transparent;
            border: none;
            color: #4caf50;
            cursor: pointer;
            text-decoration: underline;
        }
        .custom-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.9);
            overflow-y: auto;
        }
        .custom-modal-content {
            background-color: #1e1e1e;
            margin: 0 auto;
            padding: 20px;
            width: 100%;
            min-height: 100%;
            box-sizing: border-box;
        }
        .custom-close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            position: fixed;
            right: 25px;
            top: 15px;
        }
        .custom-close:hover,
        .custom-close:focus {
            color: #fff;
        }
        .custom-modal-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background-color: #2e2e2e;
            padding: 15px;
            z-index: 1001;
        }
        .custom-modal-body {
            margin-top: 70px; /* Ajusta este valor según la altura de tu encabezado */
            padding: 15px;
        }
    </style>
@endsection
