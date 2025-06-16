@extends('layouts.argon')

@section('content')

<div class="alert alert-info text-white shadow-sm" role="alert">
  <h1 class="mb-3"><i class="fas fa-user-cog"></i> Bienvenido a la Administración de Datos Personales</h1>
  <p class="mb-1">Gestione sus datos de manera segura y eficiente. Aquí puede actualizar su información personal,
    cambiar configuraciones de acceso.</p>



  <p class="mb-1"><strong>Seguridad de la Cuenta:</strong> Proteja su cuenta mediante contraseñas fuertes y la
    habilitación de autenticación en dos pasos.</p>

  <p class="mb-1"><strong>Acciones recomendadas:</strong> Recomendamos revisar y actualizar su perfil regularmente
    para mantener la información al día.</p>

 
</div>


        <div class="card shadow-lg mx-4 card-profile-bottom">
            <div class="card-body p-3">
                <div class="row gx-4">
                    <div class="col-auto">
                        <div class="avatar avatar-xl position-relative">
                            @if ($user->foto_perfil)
                                <img src="{{ asset($user->foto_perfil) }}" alt="profile_image"
                                    class="w-100 border-radius-lg shadow-sm">
                            @else
                                <img src="{{ asset('update/imagenes/user.jpg') }}" alt="profile_image"
                                class="w-100 border-radius-lg shadow-sm">
                            @endif
                        </div>
                    </div>
                    <div class="col-auto my-auto">
                        <div class="h-100">
                            <h5 class="mb-1">
                                {{ $user->usuario_nombres }} {{ $user->usuario_app }} {{ $user->usuario_apm }}
                            </h5>
                            @foreach($user->roles as $role) 
                            <p class="mb-0 font-weight-bold text-sm">
                              
                                {{$role->name;}}
                             
                               
                            </p>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                        <div class="nav-wrapper position-relative end-0">
                            <ul class="nav nav-pills nav-fill p-1" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active d-flex align-items-center justify-content-center" data-bs-toggle="tab" data-bs-target="#tab-app" role="tab" aria-selected="true">
                                        <i class="fas fa-mobile-alt"></i> 
                                        <span class="ms-2">Perfil</span>
                                    </a>

                                    
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center justify-content-center" data-bs-toggle="tab" data-bs-target="#tab-mensajes" role="tab" aria-selected="false">
                                        <i class="fas fa-envelope"></i> 
                                        <span class="ms-2">Curriculum</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center justify-content-center" data-bs-toggle="tab" data-bs-target="#tab-config" role="tab" aria-selected="false">
                                        <i class="fas fa-cogs"></i>
                                        <span class="ms-2">Configuración</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        
        <div class="container-fluid py-4">

        <div class="tab-content mt-4">
            <div class="tab-pane fade show active" id="tab-app" role="tabpanel">
                {{-- Aquí va el contenido de la aplicación --}}
                @include('usuarios.datos')
            </div>

            <div class="tab-pane fade" id="tab-mensajes" role="tabpanel">
                {{-- Aquí va el contenido de mensajes --}}
                


                @include('personal.index')
            </div>

            <div class="tab-pane fade" id="tab-config" role="tabpanel">
                {{-- Aquí va el contenido de configuración --}}
                
            </div>
        </div>
        
</div>


            
            <footer class="footer pt-3  ">
                <div class="container-fluid">
                    <div class="row align-items-center justify-content-lg-between">
                        <div class="col-lg-6 mb-lg-0 mb-4">
                            <div class="copyright text-center text-sm text-muted text-lg-start">
                                ©
                                <script>
                                    document.write(new Date().getFullYear())
                                </script>,
                                made with <i class="fa fa-heart"></i> by
                                <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Creative
                                    Tim</a>
                                for a better web.
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                                <li class="nav-item">
                                    <a href="https://www.creative-tim.com" class="nav-link text-muted"
                                        target="_blank">Creative Tim</a>
                                </li>
                                <li class="nav-item">
                                    <a href="https://www.creative-tim.com/presentation" class="nav-link text-muted"
                                        target="_blank">About Us</a>
                                </li>
                                <li class="nav-item">
                                    <a href="https://www.creative-tim.com/blog" class="nav-link text-muted"
                                        target="_blank">Blog</a>
                                </li>
                                <li class="nav-item">
                                    <a href="https://www.creative-tim.com/license" class="nav-link pe-0 text-muted"
                                        target="_blank">License</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
       
    

   


    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }

       
        function previewImage(event) {
            const file = event.target.files[0];
            const previewImg = document.getElementById("preview-img");
            const removeBtn = document.getElementById("remove-img");
            const previewContainer = document.getElementById("preview-container");

          
            if (file) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImg.src = e.target.result;  
                    previewImg.style.display = "block";  
                    removeBtn.style.display = "inline-block"; 
                }
                
                reader.readAsDataURL(file);  
            }
        }

        
        function removeImage() {
            const previewImg = document.getElementById("preview-img");
            const removeBtn = document.getElementById("remove-img");
            const inputFile = document.getElementById("profile_picture");
            
         
            previewImg.style.display = "none";
            removeBtn.style.display = "none";
            inputFile.value = ""; 
        }

    </script>


@endsection