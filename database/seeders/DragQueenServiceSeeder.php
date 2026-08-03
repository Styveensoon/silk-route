<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Publicaciones de relleno tematizadas con las reinas de "La Mas Draga" /
 * "Solo Las Mas 2", inspiradas en su personalidad y frases conocidas
 * (ej. Letal y su "amor"). Es contenido de broma para el seeding de demo,
 * no afirmaciones reales sobre las personas.
 */
class DragQueenServiceSeeder extends Seeder
{
    protected array $meetingPoints = [
        ['address' => 'San Martin Texmelucan, Puebla', 'lat' => 19.2833, 'lng' => -98.4333],
        ['address' => 'Zocalo de Puebla, Puebla', 'lat' => 19.0433, 'lng' => -98.1982],
        ['address' => 'Angelopolis, Puebla', 'lat' => 19.0086, 'lng' => -98.2519],
        ['address' => 'CU BUAP, Puebla', 'lat' => 19.0000, 'lng' => -98.2063],
        ['address' => 'Universidad Tecnologica de Puebla, Puebla', 'lat' => 19.1002, 'lng' => -98.2809],
        ['address' => 'San Pedro Cholula, Puebla', 'lat' => 19.0631, 'lng' => -98.3037],
        ['address' => 'Atlixco, Puebla', 'lat' => 18.9086, 'lng' => -98.4331],
        ['address' => 'Huejotzingo, Puebla', 'lat' => 19.1500, 'lng' => -98.4000],
        ['address' => 'Tehuacan, Puebla', 'lat' => 18.4619, 'lng' => -97.3928],
        ['address' => 'Zacatlan, Puebla', 'lat' => 19.9333, 'lng' => -97.9667],
    ];

    public function run(): void
    {
        $categories = Category::all()->keyBy('slug');
        $pointIndex = 0;

        foreach ($this->queens() as $queen) {
            $user = User::factory()->create([
                'name' => $queen['name'],
                'email' => $queen['email'],
                'role' => 'freelancer',
            ]);

            foreach ($queen['services'] as $service) {
                $point = $this->meetingPoints[$pointIndex % count($this->meetingPoints)];
                $pointIndex++;

                Service::create([
                    'user_id' => $user->id,
                    'category_id' => $categories[$service['category']]->id,
                    'title' => $service['title'],
                    'description' => $service['description'],
                    'price' => $service['price'],
                    'status' => 'active',
                    'meeting_address' => $point['address'],
                    'meeting_lat' => $point['lat'],
                    'meeting_lng' => $point['lng'],
                ]);
            }
        }
    }

    protected function queens(): array
    {
        return [
            [
                'name' => 'Letal',
                'email' => 'letal@example.com',
                'services' => [
                    ['title' => 'Buzz Lightyear parlante "My amor"', 'description' => 'Buzz Lightyear reprogramado: al quitarle el casco dice "My amooooooor" en vez del clasico. Edicion unica.', 'category' => 'diseno-grafico', 'price' => 450],
                    ['title' => 'Clases de actuacion dramatica nivel Letal', 'description' => 'Aprende a decir "amor" con 8 tonos de sufrimiento distintos. Ideal para pleitos familiares.', 'category' => 'tutorias', 'price' => 300],
                    ['title' => 'Doblaje de audios de WhatsApp mas dramaticos', 'description' => 'Le pongo un "ay, amor" a tus notas de voz para que suenen con mas sentimiento (y mas culpa).', 'category' => 'musica', 'price' => 200],
                    ['title' => 'Tarjetas de cumpleanos que siempre terminan en "amor"', 'description' => 'Diseno tarjetas personalizadas, todas cierran con "te amo, amor", sin excepcion.', 'category' => 'diseno-grafico', 'price' => 180],
                    ['title' => 'Sesion de fotos con captions pre-escritos', 'description' => 'Fotos para Instagram con frases listas, todas terminando en "amor". Minimo 100 likes de tu tia, garantizado.', 'category' => 'fotografia-y-video', 'price' => 500],
                ],
            ],
            [
                'name' => 'Kalinca Blue',
                'email' => 'kalinca@example.com',
                'services' => [
                    ['title' => 'Asesoria de imagen "azul medianoche"', 'description' => 'Te enseño a brillar sin decir una palabra. Se trata de la actitud, amor.', 'category' => 'tutorias', 'price' => 400],
                    ['title' => 'Clases de pasarela improvisada', 'description' => 'Aprende a caminar como reina en cualquier pasillo, camion o supermercado.', 'category' => 'tutorias', 'price' => 250],
                    ['title' => 'Traduccion de piropos al ingles', 'description' => 'Para que ligues en el extranjero sin quedar en verguenza. Acento poblano incluido.', 'category' => 'redaccion-y-traduccion', 'price' => 220],
                    ['title' => 'Fotografia con filtro "todo se ve mas caro"', 'description' => 'Sesion de fotos donde hasta tu cuarto rentado parece penthouse.', 'category' => 'fotografia-y-video', 'price' => 600],
                    ['title' => 'Musicalizacion de tu entrada triunfal', 'description' => 'Playlist a la medida para que entres a cualquier fiesta como quien entra a un desfile.', 'category' => 'musica', 'price' => 300],
                ],
            ],
            [
                'name' => 'Aria',
                'email' => 'aria@example.com',
                'services' => [
                    ['title' => 'Clases de guitarra "actitud primero"', 'description' => 'Los acordes se aprenden despues, primero hay que aprender a verse bien tocando.', 'category' => 'musica', 'price' => 350],
                    ['title' => 'Diseno de logo con toques goticos', 'description' => 'Para tu changarro, tu banda o tu bio de Instagram. Todo mas oscuro, todo mejor.', 'category' => 'diseno-grafico', 'price' => 400],
                    ['title' => 'Playlist para romper corazones con estilo', 'description' => 'Seleccion curada de canciones para tu drama personal, en orden cronologico del dolor.', 'category' => 'musica', 'price' => 150],
                    ['title' => 'Tutoria de ojo ahumado en 5 minutos', 'description' => 'Maquillaje expres para cuando se te hace tarde pero igual quieres verte terrible (en el buen sentido).', 'category' => 'tutorias', 'price' => 200],
                    ['title' => 'Tutoria de reparacion de audifonos', 'description' => 'El drama necesita buen sonido. Te enseño a dejar tus audifonos como nuevos, o casi.', 'category' => 'tutorias', 'price' => 180],
                ],
            ],
            [
                'name' => 'Yefri Ramirez',
                'email' => 'yefri@example.com',
                'services' => [
                    ['title' => 'Stand up personalizado sobre tu ex', 'description' => 'Rutina de comedia hecha a la medida de tu ultima relacion. Nombre y apellido opcional (recomendado).', 'category' => 'redaccion-y-traduccion', 'price' => 350],
                    ['title' => 'Clases de como cotorrear en reuniones familiares', 'description' => 'Tecnicas para ser el alma de la fiesta sin que te corran de la comida.', 'category' => 'tutorias', 'price' => 200],
                    ['title' => 'Traduccion de groserias poblanas para la abuela', 'description' => 'Te explico que significo lo que dijo tu primo, en version apta para abuelitas.', 'category' => 'redaccion-y-traduccion', 'price' => 150],
                    ['title' => 'Redaccion de brindis chuscos para bodas', 'description' => 'Discursos de boda con chistes que si dan risa (y algo de verguenza).', 'category' => 'redaccion-y-traduccion', 'price' => 180],
                    ['title' => 'Grabacion de videos comicos para redes', 'description' => 'Videos cortos con chistes locales, ideal para hacerte viral en el grupo de la escuela.', 'category' => 'fotografia-y-video', 'price' => 300],
                ],
            ],
            [
                'name' => 'Kareloz',
                'email' => 'kareloz@example.com',
                'services' => [
                    ['title' => 'Clases de comedia "fea a proposito"', 'description' => 'El arte de caerte con estilo y hacer reir sin decir una palabra.', 'category' => 'tutorias', 'price' => 250],
                    ['title' => 'Diseno de stickers ridiculos para tu grupo de WhatsApp', 'description' => 'Paquete de stickers con tu cara haciendo caras raras. Garantizado para incomodar a la familia.', 'category' => 'diseno-grafico', 'price' => 150],
                    ['title' => 'Coreografia de 30 segundos para TikTok', 'description' => 'Pasos faciles, verguenza garantizada, views no garantizados.', 'category' => 'musica', 'price' => 200],
                    ['title' => 'Sesion de fotos "mal angulo a proposito"', 'description' => 'Para el chat del grupo, no para la boda. Ideal para memes.', 'category' => 'fotografia-y-video', 'price' => 180],
                    ['title' => 'Programacion de un bot que avisa cuando tu ex esta en linea', 'description' => 'Bot de WhatsApp con alerta (y musica de suspenso) cuando tu ex se conecta. Uso bajo tu propio riesgo emocional.', 'category' => 'programacion', 'price' => 500],
                ],
            ],
            [
                'name' => 'Deborah French',
                'email' => 'deborah@example.com',
                'services' => [
                    ['title' => 'Asesoria de guardarropa capsula', 'description' => 'Te ayudo a verte carisima con presupuesto de estudiante.', 'category' => 'tutorias', 'price' => 350],
                    ['title' => 'Fotografia editorial estilo revista', 'description' => 'Sesion con locacion en tu depa, resultado de portada de revista de moda.', 'category' => 'fotografia-y-video', 'price' => 700],
                    ['title' => 'Clases de como caminar como portada', 'description' => 'Postura, actitud y tacones. Te enseño a entrar a cualquier lugar como si fuera pasarela.', 'category' => 'tutorias', 'price' => 300],
                    ['title' => 'Diseno de moodboard para tu transformacion', 'description' => 'Tablero visual para tu proximo cambio de imagen, de look casual a look "no me hables".', 'category' => 'diseno-grafico', 'price' => 220],
                    ['title' => 'Traduccion de resenas de moda del ingles', 'description' => 'Para que entiendas por que esa bolsa cuesta lo que cuesta.', 'category' => 'redaccion-y-traduccion', 'price' => 150],
                ],
            ],
            [
                'name' => 'Regia',
                'email' => 'regia@example.com',
                'services' => [
                    ['title' => 'Clases de acento regio para poblanos', 'description' => 'Aprende a decir "que buena onda, carnal" con la actitud correcta.', 'category' => 'tutorias', 'price' => 200],
                    ['title' => 'Tutoria de como presumir tu ciudad sin que te corran', 'description' => 'Tecnicas de orgullo local aplicables a Puebla, Monterrey o donde sea que naciste.', 'category' => 'tutorias', 'price' => 150],
                    ['title' => 'Diseno de playeras con orgullo local', 'description' => 'Personalizo el diseno con el nombre de tu ciudad y una frase que da flojera explicar.', 'category' => 'diseno-grafico', 'price' => 250],
                    ['title' => 'Musicalizacion con banda nortenia para tu carne asada', 'description' => 'Playlist para que la reunion familiar suene como se debe.', 'category' => 'musica', 'price' => 300],
                    ['title' => 'Redaccion de discursos regios para brindis', 'description' => 'Discursos con orgullo de tierra, listos para copiar y pegar en tu boda.', 'category' => 'redaccion-y-traduccion', 'price' => 220],
                ],
            ],
            [
                'name' => 'Marka Perrozzi',
                'email' => 'marka@example.com',
                'services' => [
                    ['title' => 'Diseno de logo "perrisimo" para tu marca', 'description' => 'Logo con actitud, para que tu changarro se vea con mordida.', 'category' => 'diseno-grafico', 'price' => 350],
                    ['title' => 'Fotografia de mascotas con actitud de pasarela', 'description' => 'Sesion de fotos para que tu perro se vea mas fashion que tu.', 'category' => 'fotografia-y-video', 'price' => 300],
                    ['title' => 'Clases de postura y actitud en pasarela', 'description' => 'Aprende a caminar con la barbilla arriba, aunque sea rumbo al Oxxo.', 'category' => 'tutorias', 'price' => 250],
                    ['title' => 'Redaccion de biografias de Instagram con mordida', 'description' => 'Bio corta, con personalidad, sin que suene a que la escribio tu mama.', 'category' => 'redaccion-y-traduccion', 'price' => 120],
                    ['title' => 'Asesoria de imagen para verte "muy top"', 'description' => 'Te ayudo a proyectar seguridad, actitud y buen gusto, sin gastar de mas.', 'category' => 'tutorias', 'price' => 300],
                ],
            ],
            [
                'name' => 'Xtremma',
                'email' => 'xtremma@example.com',
                'services' => [
                    ['title' => 'Clases de maquillaje extremo para Halloween todo el anio', 'description' => 'Tecnicas de maquillaje dramatico, para cuando el dia a dia se siente muy poco.', 'category' => 'tutorias', 'price' => 300],
                    ['title' => 'Diseno de portadas dark para tu banda', 'description' => 'Portadas con estetica oscura, ideal para tu proyecto musical o tu playlist personal.', 'category' => 'diseno-grafico', 'price' => 280],
                    ['title' => 'Fotografia de contraste alto', 'description' => 'Nada de tonos pastel. Fotos con actitud y sombras marcadas.', 'category' => 'fotografia-y-video', 'price' => 400],
                    ['title' => 'Desarrollo de sitio web modo oscuro unicamente', 'description' => 'Pagina web sencilla, pero con dark mode obligatorio. La luz no es lo mio.', 'category' => 'programacion', 'price' => 600],
                    ['title' => 'Musicalizacion industrial para tu rutina de gym', 'description' => 'Playlist pesada para que tu rutina se sienta como videoclip.', 'category' => 'musica', 'price' => 180],
                ],
            ],
            [
                'name' => 'Kim Potenzia',
                'email' => 'kim@example.com',
                'services' => [
                    ['title' => 'Clases de baile alto voltaje', 'description' => 'Coreografia con energia, ideal para tu proxima fiesta o cumpleanos.', 'category' => 'tutorias', 'price' => 350],
                    ['title' => 'Diseno de flyers con brillo digital', 'description' => 'Flyers para tu evento con purpurina en photoshop, sin que se te caiga de verdad.', 'category' => 'diseno-grafico', 'price' => 220],
                    ['title' => 'Asesoria de imagen potenciada', 'description' => 'Saca tu mejor version, con actitud de reina en cualquier reunion.', 'category' => 'tutorias', 'price' => 300],
                    ['title' => 'Fotografia con luces de escenario', 'description' => 'Sesion con iluminacion tipo concierto, aunque sea en tu cuarto.', 'category' => 'fotografia-y-video', 'price' => 500],
                    ['title' => 'Coreografia de entrada para tu cumpleanos', 'description' => 'Entrada triunfal con musica y pasos, para que tu fiesta se sienta a evento.', 'category' => 'musica', 'price' => 400],
                ],
            ],
            [
                'name' => 'Aketza',
                'email' => 'aketza@example.com',
                'services' => [
                    ['title' => 'Clases de arte conceptual para proyectos escolares', 'description' => 'Ideas visuales para que tu proyecto no se vea como tarea de ultima hora.', 'category' => 'tutorias', 'price' => 200],
                    ['title' => 'Diseno de portadas de playlist personalizadas', 'description' => 'Portadas unicas para tus playlists de Spotify, con estilo artistico.', 'category' => 'diseno-grafico', 'price' => 150],
                    ['title' => 'Redaccion creativa con toque poetico', 'description' => 'Textos, dedicatorias o descripciones con un toque mas artistico de lo normal.', 'category' => 'redaccion-y-traduccion', 'price' => 180],
                    ['title' => 'Fotografia experimental con luces de colores', 'description' => 'Sesion con iluminacion de colores, ideal para portadas o proyectos creativos.', 'category' => 'fotografia-y-video', 'price' => 350],
                    ['title' => 'Tutoria de edicion de video para reels', 'description' => 'Aprende cortes, transiciones y musica para que tus videos se vean profesionales.', 'category' => 'tutorias', 'price' => 250],
                ],
            ],
            [
                'name' => 'Ninel Reyes',
                'email' => 'ninel@example.com',
                'services' => [
                    ['title' => 'Clases de actuacion telenovela mexicana', 'description' => 'Aprende a llorar en 3 segundos y a dar la cachetada perfecta (de mentiras).', 'category' => 'tutorias', 'price' => 300],
                    ['title' => 'Redaccion de cartas dramaticas', 'description' => 'Cartas de reconciliacion o de ruptura, con el nivel de drama que la ocasion merece.', 'category' => 'redaccion-y-traduccion', 'price' => 200],
                    ['title' => 'Musicalizacion con bolero para tu drama personal', 'description' => 'Playlist de boleros para llorar con estilo y buen gusto musical.', 'category' => 'musica', 'price' => 150],
                    ['title' => 'Fotografia con efecto lagrima perfecta', 'description' => 'Retoque incluido para esa foto dramatica que necesitas para el perfil.', 'category' => 'fotografia-y-video', 'price' => 400],
                    ['title' => 'Automatizacion de mensajes dramaticos para WhatsApp', 'description' => 'Programo respuestas automaticas tipo "ya no importa" para cuando no quieras discutir mas.', 'category' => 'programacion', 'price' => 450],
                ],
            ],
            [
                'name' => 'Kacia Mizrahi',
                'email' => 'kacia@example.com',
                'services' => [
                    ['title' => 'Diseno de moodboards editoriales', 'description' => 'Tableros visuales para tu marca personal o proyecto creativo.', 'category' => 'diseno-grafico', 'price' => 250],
                    ['title' => 'Clases de postura de pasarela para entrevistas', 'description' => 'Aprende a entrar a una entrevista de trabajo con la seguridad de quien va a un desfile.', 'category' => 'tutorias', 'price' => 300],
                    ['title' => 'Traduccion de fichas tecnicas de moda', 'description' => 'Traduzco del ingles textos de moda para que no te pierdas ningun detalle.', 'category' => 'redaccion-y-traduccion', 'price' => 200],
                    ['title' => 'Fotografia de producto estilo campania de lujo', 'description' => 'Tus productos se veran como anuncio de revista, sin el presupuesto de revista.', 'category' => 'fotografia-y-video', 'price' => 550],
                    ['title' => 'Asesoria de imagen ejecutiva con toque fashion', 'description' => 'Para que te veas profesional sin perder tu esencia. Serio pero con actitud.', 'category' => 'tutorias', 'price' => 350],
                ],
            ],
            [
                'name' => 'Jacky Bond',
                'email' => 'jacky@example.com',
                'services' => [
                    ['title' => 'Clases de como entrar a una fiesta como agente secreto', 'description' => 'Entradas triunfales con estilo, discrecion opcional.', 'category' => 'tutorias', 'price' => 250],
                    ['title' => 'Diseno de tarjetas de presentacion tipo licencia especial', 'description' => 'Tarjetas con estilo de agente encubierto, para que impresiones desde el saludo.', 'category' => 'diseno-grafico', 'price' => 200],
                    ['title' => 'Fotografia con estetica de espia elegante', 'description' => 'Sesion de fotos con actitud misteriosa y mucho glamour.', 'category' => 'fotografia-y-video', 'price' => 450],
                    ['title' => 'Redaccion de mensajes de texto con suspenso', 'description' => 'Te ayudo a redactar esos mensajes que dejan a la otra persona esperando.', 'category' => 'redaccion-y-traduccion', 'price' => 150],
                    ['title' => 'Programacion de app para rastrear a quien no invitar', 'description' => 'Aplicacion sencilla para llevar registro de quien queda fuera de la lista, con notas incluidas.', 'category' => 'programacion', 'price' => 500],
                ],
            ],
            [
                'name' => 'Titiana',
                'email' => 'titiana@example.com',
                'services' => [
                    ['title' => 'Clases de diccion teatral', 'description' => 'Para presentaciones escolares donde necesitas que te escuchen hasta el ultimo asiento.', 'category' => 'tutorias', 'price' => 250],
                    ['title' => 'Redaccion de monologos dramaticos', 'description' => 'Textos para tareas de literatura o para tu proxima crisis existencial en publico.', 'category' => 'redaccion-y-traduccion', 'price' => 200],
                    ['title' => 'Fotografia con poses de obra de teatro clasica', 'description' => 'Sesion de fotos con actitud de escenario, ideal para tu portafolio artistico.', 'category' => 'fotografia-y-video', 'price' => 400],
                    ['title' => 'Tutoria de proyeccion de voz', 'description' => 'Para que te escuchen hasta atras sin necesidad de microfono.', 'category' => 'tutorias', 'price' => 220],
                    ['title' => 'Diseno de programas de mano para eventos escolares', 'description' => 'Programas impresos con buen diseno, para que tu evento se vea mas profesional.', 'category' => 'diseno-grafico', 'price' => 180],
                ],
            ],
        ];
    }
}
