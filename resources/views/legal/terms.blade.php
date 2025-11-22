@extends('layouts.shop')

@section('title', 'Términos y Condiciones')

@section('content')
<div class="bg-gradient-to-br from-red-50 to-yellow-50 py-12">
    <div class="container mx-auto px-4 max-w-4xl">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-8 border-t-4 border-[#d81d25]">
            <div class="text-center mb-6">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Términos y Condiciones</h1>
                <p class="text-gray-600">SUPERMAX S.A.</p>
                <p class="text-sm text-gray-500 mt-2">Última actualización: {{ date('d/m/Y') }}</p>
            </div>
        </div>

        <!-- Contenido -->
        <div class="bg-white rounded-2xl shadow-xl p-8 space-y-8">
            
            <!-- 1. Información General -->
            <section>
                <h2 class="text-2xl font-bold text-[#d81d25] mb-4 flex items-center">
                    <span class="bg-[#ffd90f] text-gray-900 w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm font-bold">1</span>
                    Información General
                </h2>
                <div class="ml-11 space-y-3 text-gray-700">
                    <p><strong>Razón Social:</strong> SUPERMAX S.A.</p>
                    <p><strong>Domicilio Legal:</strong> Av. Maipu 359, Capital, Corrientes, Argentina</p>
                    <p><strong>CUIT:</strong> (número fiscal)</p>
                    <p><strong>Email:</strong> <a href="mailto:supermax@supermaxsa.com.ar" class="hover:underline">supermax@supermaxsa.com.ar</a></p>
                    <p><strong>Teléfono:</strong> <a href="tel:+543794000000" class="hover:underline">+54 379 400-0000</a></p>
                </div>
            </section>

            <!-- 2. Aceptación de Términos -->
            <section>
                <h2 class="text-2xl font-bold text-[#d81d25] mb-4 flex items-center">
                    <span class="bg-[#ffd90f] text-gray-900 w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm font-bold">2</span>
                    Aceptación de Términos
                </h2>
                <div class="ml-11 space-y-3 text-gray-700">
                    <p>Al realizar un pedido en nuestro sitio web, usted acepta y se compromete a cumplir con estos Términos y Condiciones. Si no está de acuerdo con alguno de los términos aquí establecidos, le solicitamos que no utilice nuestros servicios.</p>
                </div>
            </section>

            <!-- 3. Pedidos y Confirmación -->
            <section>
                <h2 class="text-2xl font-bold text-[#d81d25] mb-4 flex items-center">
                    <span class="bg-[#ffd90f] text-gray-900 w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm font-bold">3</span>
                    Pedidos y Confirmación
                </h2>
                <div class="ml-11 space-y-3 text-gray-700">
                    <p><strong>Pedido Mínimo:</strong> El monto mínimo para realizar una compra es de <span class="font-semibold">$10,000</span>.</p>
                    <p><strong>Confirmación:</strong> El pedido se confirma automáticamente una vez que el pago es aprobado por MercadoPago.</p>
                    <p><strong>Disponibilidad:</strong> Todos los productos están sujetos a disponibilidad. En caso de no contar con stock, nos comunicaremos con usted para ofrecer alternativas o proceder con el reembolso correspondiente.</p>
                </div>
            </section>

            <!-- 4. Entrega -->
            <section>
                <h2 class="text-2xl font-bold text-[#d81d25] mb-4 flex items-center">
                    <span class="bg-[#ffd90f] text-gray-900 w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm font-bold">4</span>
                    Política de Entrega
                </h2>
                <div class="ml-11 space-y-3 text-gray-700">
                    <p><strong>Zona de Cobertura:</strong> Realizamos entregas únicamente en toda la ciudad capital de Corrientes.</p>
                    <p><strong>Horario de Entrega:</strong> Las entregas se realizan durante todo el día de la fecha que usted haya seleccionado al momento de realizar el pedido.</p>
                    <p><strong>Entregas Urgentes:</strong> No ofrecemos servicio de entregas urgentes o fuera del horario establecido.</p>
                    <p><strong>Responsabilidad:</strong> Es responsabilidad del cliente proporcionar una dirección completa y correcta. No nos hacemos responsables por demoras ocasionadas por direcciones incorrectas o incompletas.</p>
                </div>
            </section>

            <!-- 5. Métodos de Pago -->
            <section>
                <h2 class="text-2xl font-bold text-[#d81d25] mb-4 flex items-center">
                    <span class="bg-[#ffd90f] text-gray-900 w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm font-bold">5</span>
                    Métodos de Pago
                </h2>
                <div class="ml-11 space-y-3 text-gray-700">
                    <p><strong>Plataforma Aceptada:</strong> Únicamente aceptamos pagos a través de <span class="font-semibold">MercadoPago</span>.</p>
                    <p><strong>Métodos Disponibles en MercadoPago:</strong></p>
                    <ul class="list-disc ml-6 space-y-1">
                        <li>Tarjetas de crédito y débito</li>
                        <li>Pago en efectivo (Rapipago, Pago Fácil)</li>
                        <li>Dinero en cuenta de MercadoPago</li>
                    </ul>
                    <p><strong>Seguridad:</strong> Todos los pagos son procesados de forma segura por MercadoPago. No almacenamos información de tarjetas de crédito en nuestros servidores.</p>
                </div>
            </section>

            <!-- 6. Cancelaciones -->
            <section>
                <h2 class="text-2xl font-bold text-[#d81d25] mb-4 flex items-center">
                    <span class="bg-[#ffd90f] text-gray-900 w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm font-bold">6</span>
                    Política de Cancelación
                </h2>
                <div class="ml-11 space-y-3 text-gray-700">
                    <p><strong>Plazo para Cancelar:</strong> Puede cancelar su pedido sin costo <span class="font-semibold">hasta 24 horas antes</span> de la fecha de entrega seleccionada.</p>
                    <p><strong>Después del Plazo:</strong> Pasadas las 24 horas previas a la entrega, <span class="font-semibold">no se aceptan cancelaciones</span> debido a que ya comienza la preparación de los productos.</p>
                    <p><strong>Cómo Cancelar:</strong> Para cancelar su pedido, contáctenos por email a <a href="mailto:supermax@supermaxsa.com.ar" class="hover:underline">supermax@supermaxsa.com.ar</a> o por teléfono al <a href="tel:+543794000000" class="hover:underline">+54 379 400-0000</a> indicando su número de pedido.</p>
                </div>
            </section>

            <!-- 7. Reembolsos -->
            <section>
                <h2 class="text-2xl font-bold text-[#d81d25] mb-4 flex items-center">
                    <span class="bg-[#ffd90f] text-gray-900 w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm font-bold">7</span>
                    Política de Reembolsos
                </h2>
                <div class="ml-11 space-y-3 text-gray-700">
                    <p><strong>Cancelación Dentro del Plazo:</strong> Si cancela dentro de las 24 horas previas permitidas, recibirá un <span class="font-semibold">reembolso completo del 100%</span>.</p>
                    <p><strong>Cancelación Fuera del Plazo:</strong> No se otorgarán reembolsos si la cancelación se solicita fuera del plazo establecido.</p>
                    <p><strong>Error del Negocio:</strong> En caso de error atribuible a SUPERMAX (producto incorrecto, no entrega, producto en mal estado), se procederá con el reembolso completo o el reemplazo del producto según su preferencia.</p>
                    <p><strong>Procesamiento:</strong> Los reembolsos se procesan a través de MercadoPago y pueden tardar entre 5 a 10 días hábiles en reflejarse en su cuenta, dependiendo de su entidad bancaria.</p>
                </div>
            </section>

            <!-- 8. Devoluciones -->
            <section>
                <h2 class="text-2xl font-bold text-[#d81d25] mb-4 flex items-center">
                    <span class="bg-[#ffd90f] text-gray-900 w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm font-bold">8</span>
                    Política de Devoluciones
                </h2>
                <div class="ml-11 space-y-3 text-gray-700">
                    <p><strong>Productos Frescos:</strong> Por la naturaleza de nuestros productos (alimentos frescos y preparados), <span class="font-semibold">no aceptamos devoluciones</span>, salvo que exista una falla evidente atribuible a nuestro servicio.</p>
                    <p><strong>Casos Excepcionales:</strong> Si recibe un producto en mal estado, incorrecto o defectuoso, ofrecemos:</p>
                    <ul class="list-disc ml-6 space-y-1">
                        <li>Reemplazo del producto sin costo adicional</li>
                        <li>Crédito equivalente para su próxima compra</li>
                        <li>Reembolso completo del importe</li>
                    </ul>
                    <p><strong>Reclamo:</strong> Debe notificarnos cualquier inconveniente el mismo día de la entrega para poder procesar su reclamo.</p>
                </div>
            </section>

            <!-- 9. Cambios en el Pedido -->
            <section>
                <h2 class="text-2xl font-bold text-[#d81d25] mb-4 flex items-center">
                    <span class="bg-[#ffd90f] text-gray-900 w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm font-bold">9</span>
                    Modificaciones del Pedido
                </h2>
                <div class="ml-11 space-y-3 text-gray-700">
                    <p><strong>Plazo para Modificar:</strong> Puede solicitar cambios en su pedido <span class="font-semibold">hasta 24 horas antes</span> de la fecha de entrega.</p>
                    <p><strong>Disponibilidad:</strong> Los cambios están siempre sujetos a disponibilidad de productos.</p>
                    <p><strong>Después del Plazo:</strong> Pasadas las 24 horas previas, <span class="font-semibold">no se permiten modificaciones</span> debido al inicio de preparación.</p>
                    <p><strong>Diferencia de Precio:</strong> Si el cambio implica un monto superior, deberá abonar la diferencia. Si es inferior, se generará un crédito para futuras compras.</p>
                </div>
            </section>

            <!-- 10. Eventos Especiales -->
            <section>
                <h2 class="text-2xl font-bold text-[#d81d25] mb-4 flex items-center">
                    <span class="bg-[#ffd90f] text-gray-900 w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm font-bold">10</span>
                    Eventos Especiales
                </h2>
                <div class="ml-11 space-y-3 text-gray-700">
                    <p>Los pedidos para eventos especiales (fiestas, reuniones corporativas, etc.) están sujetos a las mismas condiciones generales descritas en estos términos. No existen condiciones diferentes o excepcionales para este tipo de pedidos.</p>
                </div>
            </section>

            <!-- 11. Privacidad y Protección de Datos -->
            <section>
                <h2 class="text-2xl font-bold text-[#d81d25] mb-4 flex items-center">
                    <span class="bg-[#ffd90f] text-gray-900 w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm font-bold">11</span>
                    Privacidad y Protección de Datos
                </h2>
                <div class="ml-11 space-y-3 text-gray-700">
                    <p>Nos comprometemos a proteger su información personal conforme a la Ley de Protección de Datos Personales N° 25.326. Sus datos serán utilizados únicamente para procesar y entregar su pedido.</p>
                    <p><strong>Uso de Datos:</strong></p>
                    <ul class="list-disc ml-6 space-y-1">
                        <li>Procesamiento de pedidos</li>
                        <li>Comunicación sobre el estado de su pedido</li>
                        <li>Mejora de nuestros servicios</li>
                    </ul>
                    <p>No compartimos ni vendemos su información personal a terceros sin su consentimiento expreso.</p>
                </div>
            </section>

            <!-- 12. Limitación de Responsabilidad -->
            <section>
                <h2 class="text-2xl font-bold text-[#d81d25] mb-4 flex items-center">
                    <span class="bg-[#ffd90f] text-gray-900 w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm font-bold">12</span>
                    Limitación de Responsabilidad
                </h2>
                <div class="ml-11 space-y-3 text-gray-700">
                    <p>SUPERMAX S.A. no se hace responsable por:</p>
                    <ul class="list-disc ml-6 space-y-1">
                        <li>Demoras ocasionadas por información incorrecta proporcionada por el cliente</li>
                        <li>Casos de fuerza mayor que impidan la entrega (condiciones climáticas extremas, manifestaciones, etc.)</li>
                        <li>Problemas técnicos con la plataforma de pago de terceros (MercadoPago)</li>
                        <li>Alergias o intolerancias alimentarias no informadas previamente</li>
                    </ul>
                </div>
            </section>

            <!-- 13. Modificaciones a los Términos -->
            <section>
                <h2 class="text-2xl font-bold text-[#d81d25] mb-4 flex items-center">
                    <span class="bg-[#ffd90f] text-gray-900 w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm font-bold">13</span>
                    Modificaciones a los Términos
                </h2>
                <div class="ml-11 space-y-3 text-gray-700">
                    <p>SUPERMAX S.A. se reserva el derecho de modificar estos Términos y Condiciones en cualquier momento. Las modificaciones serán efectivas inmediatamente después de su publicación en el sitio web. Le recomendamos revisar periódicamente esta página para estar informado de cualquier cambio.</p>
                </div>
            </section>

            <!-- 14. Ley Aplicable y Jurisdicción -->
            <section>
                <h2 class="text-2xl font-bold text-[#d81d25] mb-4 flex items-center">
                    <span class="bg-[#ffd90f] text-gray-900 w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm font-bold">14</span>
                    Ley Aplicable y Jurisdicción
                </h2>
                <div class="ml-11 space-y-3 text-gray-700">
                    <p>Estos Términos y Condiciones se rigen por las leyes de la República Argentina. Cualquier controversia derivada de estos términos será sometida a los tribunales ordinarios de la ciudad de Corrientes, Argentina.</p>
                </div>
            </section>

            <!-- 15. Contacto -->
            <section>
                <h2 class="text-2xl font-bold text-[#d81d25] mb-4 flex items-center">
                    <span class="bg-[#ffd90f] text-gray-900 w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm font-bold">15</span>
                    Contacto
                </h2>
                <div class="ml-11 space-y-3 text-gray-700">
                    <p>Para cualquier consulta, reclamo o solicitud relacionada con estos Términos y Condiciones, puede contactarnos a través de:</p>
                    <div class="bg-gray-50 rounded-lg p-4 mt-4 border-l-4 border-[#d81d25]">
                        <p><strong>Email:</strong> <a href="mailto:supermax@supermaxsa.com.ar" class="hover:underline font-medium">supermax@supermaxsa.com.ar</a></p>
                        <p><strong>Teléfono:</strong> <a href="tel:+543794000000" class="hover:underline font-medium">+54 379 400-0000</a></p>
                        <p><strong>Dirección:</strong> Av. Maipu 359, Capital, Corrientes, Argentina</p>
                        <p><strong>Horario de Atención:</strong> Lunes a Viernes de 8:00 a 18:00 hs</p>
                    </div>
                </div>
            </section>

        </div>

        <!-- Footer -->
        <div class="mt-8 text-center">
            <a href="{{ route('home') }}" class="inline-block bg-[#d81d25] text-white px-8 py-3 rounded-lg hover:bg-red-700 transition font-medium shadow-lg">
                ← Volver al Inicio
            </a>
        </div>
    </div>
</div>
@endsection
