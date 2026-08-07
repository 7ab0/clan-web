@extends('layout.layout')

@php
    $title = 'La Carta';
    $subTitle = 'La Carta';

    $cartaCategorias = [
        [
            'nombre' => 'Entorno',
            'intro' => 'Nuestros piqueos se enfocan en la despensa que nos envuelve en esta tierra volcánica.',
            'platos' => [
                [
                    'nombre' => 'Geometría del Siwichi',
                    'precio' => 'S/40',
                    'desc' => 'Ceviche a nuestro estilo con conchas de abanico, langostinos y pesca del día, servido sobre leche de tigre de guiñapo, tumbo y maracuyá.',
                    'img' => 'clan-menu-geometria-siwichi.jpg',
                ],
                [
                    'nombre' => 'Habitante de las Rocas',
                    'precio' => 'S/40',
                    'desc' => 'Causa crocante de tubérculos andinos con tumbo, tartar de pulpo y salsa de olivo con colágeno marino.',
                    'img' => null,
                ],
                [
                    'nombre' => 'El Fruto de la Red',
                    'precio' => 'S/40',
                    'desc' => 'Tiradito de trucha curada con emulsión de api.',
                    'img' => null,
                ],
                [
                    'nombre' => 'Peregrinaje del Batán',
                    'precio' => 'S/36',
                    'desc' => 'Pesto andino con focaccia artesanal de api y shiitake.',
                    'img' => null,
                ],
            ],
        ],
        [
            'nombre' => 'Legado',
            'intro' => 'Nuestras entradas se inspiran en los vestigios del hollín de un fogón pasado.',
            'platos' => [
                [
                    'nombre' => 'Depredación en el Litoral',
                    'precio' => 'S/36',
                    'desc' => 'Cremoso de hongo shiitake con chips de tubérculos.',
                    'img' => null,
                ],
                [
                    'nombre' => 'Primera Cosecha',
                    'precio' => 'S/40',
                    'desc' => 'Trufa de chocolate con camote, relleno de trucha curada y emulsión de api.',
                    'img' => 'clan-menu-primera-cosecha.jpg',
                ],
                [
                    'nombre' => 'Miscelio y Corteza',
                    'precio' => 'S/36',
                    'desc' => 'Hongos caramelizados rellenos de cremado de quesos.',
                    'img' => null,
                ],
                [
                    'nombre' => 'La Forma del Humo',
                    'precio' => 'S/40',
                    'desc' => 'Tapa de jamón de picaña sobre pan artesanal.',
                    'img' => 'clan-menu-forma-humo.jpg',
                ],
            ],
        ],
        [
            'nombre' => 'Reencuentro',
            'intro' => 'En la cocina ancestral está cifrada la clave de quiénes somos.',
            'platos' => [
                [
                    'nombre' => 'Culpa Carnal',
                    'precio' => 'S/65',
                    'desc' => 'Jamón de corazón de res ahumado en salsa de guiñapo con pasta al pesto estilo oriental.',
                    'img' => 'clan-menu-culpa-carnal.jpg',
                ],
                [
                    'nombre' => 'Dialecto del Fuego',
                    'precio' => 'S/66',
                    'desc' => 'Lomo fino flameado con langostinos y conchas de abanico, sobre puré de camote y tumbo.',
                    'img' => 'clan-menu-dialecto-fuego.jpg',
                ],
                [
                    'nombre' => 'El Rumor del Hollín',
                    'precio' => 'S/75',
                    'desc' => 'Picaña ahumada con lasagna de cinghiale.',
                    'img' => 'clan-menu-rumor-hollin.jpg',
                ],
                [
                    'nombre' => 'El Eco del Tuétano',
                    'precio' => 'S/65',
                    'desc' => 'Tartar de picaña sobre tamal verde crocante.',
                    'img' => 'clan-menu-eco-tuetano.jpg',
                ],
                [
                    'nombre' => 'Tormenta en el Lago',
                    'precio' => 'S/66',
                    'desc' => 'Trucha curada con puré de tubérculos andinos, pesto y mix de nuestro huerto.',
                    'img' => 'clan-menu-tormenta-lago.jpg',
                ],
            ],
        ],
        [
            'nombre' => 'Raíz',
            'intro' => 'Viaja con nosotros a los planos lejanos de nuestra despensa.',
            'platos' => [
                [
                    'nombre' => 'Alhaja de Roca',
                    'precio' => 'S/30',
                    'desc' => 'Helado de pulpo en conchas de chocolate blanco.',
                    'img' => null,
                ],
                [
                    'nombre' => 'Deshielo del Cielo',
                    'precio' => 'S/26',
                    'desc' => 'Sorbete de sandía, cola escocesa y rocoto.',
                    'img' => null,
                ],
                [
                    'nombre' => 'Barca de Papel',
                    'precio' => null,
                    'desc' => 'Mousse de manjar de zapallo con tierra de tubérculos andinos.',
                    'img' => 'clan-menu-barca-papel.jpg',
                ],
            ],
        ],
    ];
@endphp

@section('content')

<!-- Start Carta Intro -->
<section class="carta-section">
    <div class="ak-height-150 ak-height-lg-60"></div>
    <div class="container">
        <div class="carta-intro">
            <p class="carta-eyebrow manifiesto-reveal">La Carta</p>
            <p class="carta-lead manifiesto-reveal">
                CLAN revalora la historia de la cocina peruana con el propósito de crear
                experiencias que conecten territorio y memoria, como el hollín es el
                recuerdo del fogón que alimentó el cuerpo.
            </p>
        </div>
    </div>
    <div class="ak-height-100 ak-height-lg-60"></div>
</section>
<!-- End Carta Intro -->

@foreach ($cartaCategorias as $categoria)
<!-- Start Carta {{ $categoria['nombre'] }} -->
<section class="carta-section">
    <div class="container">
        <div class="carta-category">
            <h2 class="carta-category-title anim-title">{{ $categoria['nombre'] }}</h2>
            <p class="carta-category-intro manifiesto-reveal">{{ $categoria['intro'] }}</p>

            @foreach ($categoria['platos'] as $plato)
            <div class="carta-dish manifiesto-reveal">
                <div class="carta-dish-media">
                    @if ($plato['img'])
                        <img src="{{ asset('assets/img/' . $plato['img']) }}" alt="{{ $plato['nombre'] }}">
                    @else
                        {{-- TODO(Clan): sin foto disponible aun para este plato --}}
                        <div class="carta-dish-placeholder">
                            <span>Foto&nbsp;pendiente</span>
                        </div>
                    @endif
                </div>
                <div class="carta-dish-text">
                    <div class="carta-dish-header">
                        <h3 class="carta-dish-name">{{ $plato['nombre'] }}</h3>
                        @if ($plato['precio'])
                            <span class="carta-dish-price">{{ $plato['precio'] }}</span>
                        @endif
                    </div>
                    <p class="carta-dish-desc">{{ $plato['desc'] }}</p>
                </div>
            </div>
            @endforeach

        </div>
    </div>
    <div class="ak-height-100 ak-height-lg-60"></div>
</section>
<!-- End Carta {{ $categoria['nombre'] }} -->
@endforeach

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;
        gsap.utils.toArray('.manifiesto-reveal').forEach(function (el) {
            gsap.fromTo(el,
                { autoAlpha: 0, y: 30 },
                {
                    autoAlpha: 1, y: 0, duration: 1, ease: 'power2.out',
                    scrollTrigger: { trigger: el, start: 'top 90%' }
                }
            );
        });
    });
</script>

@endsection
