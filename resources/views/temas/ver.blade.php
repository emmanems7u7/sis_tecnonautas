@extends('layouts.argon')

@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Agregar enlaces a Bootstrap CSS y Font Awesome para los iconos -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <!-- Agregar estilos personalizados para niños -->
        <style>
            h1 {
                text-align: center;
                color: #FF5722;
                margin-top: 20px;
                text-shadow: 2px 2px 4px #FFC107;
            }

            .rectangle {
                background-color: #FFF;
                border: 2px solid #FF5722;
                border-radius: 15px;
                padding: 20px;
                transition: transform 0.2s ease-in-out;
                cursor: pointer;
            }

            .rectangle:hover {
                transform: scale(1.05);
            }

            .card {
                border: none;
                margin-bottom: 20px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            .card-header {
                background-color: #FF5722;
                color: #FFF;
                text-align: center;
            }

            .card-header i {
                margin-right: 10px;
            }

            .card-body {
                padding: 20px;
            }

            .check-icon {
                color: #4CAF50;
                margin-right: 10px;
            }

            .cross-icon {
                color: #F44336;
                margin-right: 10px;
            }

            .card-c {
                background-color: #237899;
            }
        </style>
    </head>

    <body>


        <div class="container mt-5">

        </div>

        <div class="container-fluid mt-4">
            <h1>¡Bienvenidos a la Ofimática!</h1>
            <div class="row">
                <!-- Columna 1: Lista de contenidos de Ofimática para Niños -->
                <div class="col-md-5">
                    <div class="rectangle">
                        <h3>Contenidos de Ofimática</h3>
                        <div class="card">
                            <div class=" accordion" id="accordionExample">
                                <div class="card">
                                    <div class="card-header card-c" id="headingOne">
                                        <h5 class="mb-0">
                                            <button class="text-white btn btn-link" data-toggle="collapse"
                                                data-target="#collapseOne">
                                                Microsoft Word
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne"
                                        data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="card">contenido 1</div>
                                            <div class="card">contenido 1</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="headingTwo">
                                        <h5 class="mb-0">
                                            <button class="text-white btn btn-link" data-toggle="collapse"
                                                data-target="#collapseTwo">
                                                Elemento 2
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                        data-parent="#accordionExample">
                                        <div class="card-body">
                                            Contenido del elemento 2.
                                        </div>
                                    </div>
                                </div>
                                <!-- Agrega más elementos -->
                                <div class="card">
                                    <div class="card-header" id="headingThree">
                                        <h5 class="mb-0">
                                            <button class=" text-white btn btn-link" data-toggle="collapse"
                                                data-target="#collapseThree">
                                                Elemento 3
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                                        data-parent="#accordionExample">
                                        <div class="card-body">
                                            Contenido del elemento 3.
                                        </div>
                                        <div class="card-body">
                                            Contenido del elemento 3.
                                        </div>
                                        <div class="card-body">
                                            Contenido del elemento 3.
                                        </div>
                                        <div class="card-body">
                                            Contenido del elemento 3.
                                        </div>
                                    </div>
                                </div>
                                <!-- Agrega más elementos -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Columna 2: Lista de exámenes y tareas para Niños -->
                <div class="col-md-6">
                    <div class="row">
                        <!-- Primera fila: Lista de exámenes -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <i class="far fa-clipboard-check check-icon"></i> Exámenes
                                </div>
                                <div class="card-body">
                                    <div class="card">
                                        <div class="card-body">
                                            <p><i class="far fa-check-circle check-icon"></i> Examen 1</p>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-body">
                                            <p><i class="far fa-check-circle check-icon"></i> Examen 2</p>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-body">
                                            <p><i class="far fa-times-circle cross-icon"></i> Examen 3</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Segunda fila: Lista de tareas -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <i class="far fa-clipboard cross-icon"></i> Tareas
                                </div>
                                <div class="card-body">
                                    <div class="card">
                                        <div class="card-body">
                                            <p><i class="far fa-check-circle check-icon"></i> Tarea 1</p>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-body">
                                            <p><i class="far fa-times-circle cross-icon"></i> Tarea 2</p>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-body">
                                            <p><i class="far fa-times-circle cross-icon"></i> Tarea 3</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    </body>

    </html>

@endsection