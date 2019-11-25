<?php

use App\Helpers\Registry;
//use Illuminate\Support\Facades\Storage;
//use Intervention\Image\Facades\Image;
use GuzzleHttp\Client;
use Carbon\Carbon;

function parseLocale()
{
    // Busca a primeira rota da URL
    $locale     = Request::segment(1); // Define os locales da aplicação
    $languages  = ['pt-BR', 'en', 'es'];

    // Verifica se o locale passado na URL é válido
    // Se sim, então muda o idioma da aplicação e retorna o locale
    // Se não, então deixa o idioma padrão
    if (in_array($locale, $languages)) {
        App::setLocale($locale);

        return $locale;
    }

    return '/';
}

function apiRequest($method, $url, array $options = [])
{
    $user   = auth()->user();
    $client = new Client();

    $options2 = [
        'verify'        => false,
        'exceptions'    => false,
        'headers'       => [
            'Authorization' => 'Bearer ' . $user->access_token,
            'Content-Type'  => 'application/x-www-form-urlencoded',
            'Accept'        => 'application/json',
        ],
    ];

    $options    = array_merge($options2, $options);
    $response   = $client->request($method, env('API_URL') . $url, $options);
    $contents   = json_decode($response->getBody()->getContents());
    $array      = array_merge(['statusCode' => $response->getStatusCode()], json_decode(json_encode($contents), true));

    return json_decode(json_encode($array));
}

function error($message)
{
    if (config('app.debug')) {
        throw new \ErrorException($message);
    } else {
        abort(500);
    }
}

function notfound($msg = null)
{
    if (null === $msg) {
        $msg = 'A informação requerida não foi encontrada.';
    }

    return view('info')->with('warning', $msg);
}

function unauthorized()
{
    abort(403, 'Não autorizado');
}

function estadocivil($ec = null)
{
    $itens = [
        'S' => 'Solteiro(a)',
        'C' => 'Casado(a)',
        'V' => 'Viúvo(a)',
        'D' => 'Divorciado(a)',
        'E' => 'Desquitado(a)',
        'A' => 'Amasiado(a)',
        'I' => 'Separado(a)'
    ];

    if (isset($ec)) {
        return $itens[$ec];
    }

    return $itens;
}

function agora()
{
    return date('Y-m-d H:i:s');
}

function datetime($value, $to)
{
	if (null === $value) {
        return null;
    }

    $carbon = Carbon::createFromTimestamp(strtotime($value));

    return $carbon->format($to);
}

function simnao($value)
{
    $options = [0 => 'Não', 1 => 'Sim'];

    return $options[(int) $value];
}

function sexo($value)
{
    $options = ['M' => 'Masculino', 'F' => 'Feminino'];

    return $options[$value];
}

function moeda2float($value)
{
    if (empty($value)) {
        return null;
    }

	$new = str_replace('.', '', $value);

	return str_replace(',', '.', $new);
}

function money($value)
{
    return number_format($value, 2, ',', '.');
}

function cpfcnpj($cpfcnpj)
{
	$cpfcnpj = preg_replace('/[^0-9]/', '', $cpfcnpj);

    if (strlen($cpfcnpj) === 11) {
        $new = '';

        for ($i=0; $i<11; $i++) {
            if (in_array($i, array(3, 6))) {
                $new .= '.';
            }

            if ($i == 9) {
                $new .= '-';
            }

            $new .= $cpfcnpj[$i];
        }

        return $new;
    } elseif (strlen($cpfcnpj) === 14) {
        $new = '';

        for ($i=0; $i<14; $i++) {
            if (in_array($i, array(2, 5))) {
                $new .= '.';
            }

            if ($i == 8) {
                $new .= '/';
            }

            if ($i == 12) {
                $new .= '-';
            }

            $new .= $cpfcnpj[$i];
        }

        return $new;
    }

    return null;
}

function cep($cep)
{
	if (strlen($cep) != 8) {
        return $cep;
    }

    $new = '';

    for ($i=0; $i<8; $i++) {
        if ($i === 5) {
            $new .= '-';
        }

        $new .= $cep[$i];
    }

    return $new;
}

function generateRandomString($size = 32)
{
    return substr(md5(uniqid(microtime(), 1)) . getmypid(), 0, $size);
}

function estados($sigla = null)
{
    $estados = [
        'AC' => 'Acre',
        'AL' => 'Alagoas',
        'AM' => 'Amazonas',
        'AP' => 'Amapá',
        'BA' => 'Bahia',
        'CE' => 'Ceará',
        'DF' => 'Distrito Federal',
        'ES' => 'Espírito Santos',
        'GO' => 'Goiás',
        'MA' => 'Maranhão',
        'MG' => 'Minas Gerais',
        'MT' => 'Mato Grosso',
        'MS' => 'Mato Grosso do Sul',
        'PA' => 'Pará',
        'PB' => 'Paraíba',
        'PE' => 'Pernambuco',
        'PI' => 'Piauí',
        'PR' => 'Paraná',
        'RJ' => 'Rio de Janeiro',
        'RN' => 'Rio Grande do Norte',
        'RO' => 'Rondônia',
        'RR' => 'Roraima',
        'RS' => 'Rio Grande do Sul',
        'SC' => 'Santa Catarina',
        'SE' => 'Sergipe',
        'SP' => 'São Paulo',
        'TO' => 'Tocantins'
    ];

    if (null === $sigla) {
        return $estados;
    }

    return $estados[$sigla];
}

function meses($mes = null)
{
    $meses = [
        1 => 'Janeiro',
        2 => 'Fevereiro',
        3 => 'Março',
        4 => 'Abril',
        5 => 'Maio',
        6 => 'Junho',
        7 => 'Julho',
        8 => 'Agosto',
        9 => 'Setembro',
        10 => 'Outubro',
        11 => 'Novembro',
        12 => 'Dezembro'
    ];

    if (null === $mes) {
        return $meses;
    }

    return $meses[(int) $mes];
}

function graus($grau = null)
{
    $graus = array(
        0 => 'Não informado',
        1 => 'Baixo',
        2 => 'Médio',
        3 => 'Alto'
    );

    if (null === $grau) {
        return $graus;
    }

    return $graus[$grau];
}

function situacoesCliente($situacao = null)
{
    $situacoes = array(
        'A' => 'Ativo',
        'P' => 'Passivo'
    );

    if (null === $situacao) {
        return $situacoes;
    }

    return $situacoes[$situacao];
}

function situacoesPartecontraria($situacao = null)
{
    $situacoes = array(
        'A' => 'Ativo',
        'P' => 'Passivo'
    );

    if (null === $situacao) {
        return $situacoes;
    }

    return $situacoes[$situacao];
}

function situacoesProcesso($situacao = null)
{
    $situacoes = array(
        '1' => 'Ativo',
        '2' => 'Arquivado'
    );

    if (null === $situacao) {
        return $situacoes;
    }

    return $situacoes[$situacao];
}

function tiposCobrancaMesaBilhar($tipo = null)
{
    $tipos = array(
        '1' => 'Por ficha',
        '2' => 'Valor fixo'
    );

    if (null === $tipo) {
        return $tipos;
    }

    return $tipos[$tipo];
}

/*function situacoesAssinatura($situacao = null)
{
    $situacoes = array(
        0 => 'Em aberto',
        1 => 'Baixado',
    );

    if (null === $situacao) {
        return $situacoes;
    }

    return $situacoes[$situacao];
}*/

function ultimoDiaMes($mes, $ano)
{
    $dias = [
        1 => 31,
        2 => 28,
        3 => 31,
        4 => 30,
        5 => 31,
        6 => 30,
        7 => 31,
        8 => 31,
        9 => 30,
        10 => 31,
        11 => 30,
        12 => 31
    ];

    if (($ano % 4) === 0) {
        $dias[2] = 29;
    }

    return $ano . '-' . sprintf('%02s', $mes) . '-' . $dias[(int) $mes];
}

function valorPorExtenso($valor, $moeda = true)
{
    $rt = '';

    if ($moeda) {
        $singular = array('centavo', 'real', 'mil', 'milhão', 'bilhão', 'trilhão', 'quatrilhão');
        $plural = array('centavos', 'reais', 'mil', 'milhões', 'bilhões', 'trilhões', 'quatrilhões');
    } else {
        $singular = array('', '', 'mil', 'milhão', 'bilhão', 'trilhão', 'quatrilhão');
        $plural = array('', '', 'mil', 'milhões', 'bilhões', 'trilhões', 'quatrilhões');
    }

    $c = array('', 'cem', 'duzentos', 'trezentos', 'quatrocentos',
        'quinhentos', 'seiscentos', 'setecentos', 'oitocentos', 'novecentos');

    $d = array('', 'dez', 'vinte', 'trinta', 'quarenta', 'cinquenta',
        'sessenta', 'setenta', 'oitenta', 'noventa');

    $d10 = array('dez', 'onze', 'doze', 'treze', 'quatorze', 'quinze',
        'dezesseis', 'dezessete', 'dezoito', 'dezenove');

    $u = array('', 'um', 'dois', 'três', 'quatro', 'cinco', 'seis',
        'sete', 'oito', 'nove');

    $z = 0;

    $valor = number_format($valor, 2, '.', '.');
    $inteiro = explode('.', $valor);

    for ($i = 0; $i < count($inteiro); $i++) {
        for ($ii = strlen($inteiro[$i]); $ii < 3; $ii++) {
            $inteiro[$i] = '0' . $inteiro[$i];
        }
    }

    // $fim identifica onde que deve se dar juncao de centenas por "e" ou por "," ;)
    $fim = count($inteiro) - ($inteiro[count($inteiro) - 1] > 0 ? 1 : 2);

    for ($i = 0; $i < count($inteiro); $i++) {
        $valor = $inteiro[$i];
        $rc = (($valor > 100) && ($valor < 200)) ? 'cento' : $c[$valor[0]];
        $rd = ($valor[1] < 2) ? '' : $d[$valor[1]];
        $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : '';

        $r = $rc . (($rc && ($rd || $ru)) ? ' e ' : '') . $rd . (($rd && $ru) ? ' e ' : '') . $ru;
        $t = count($inteiro) - 1 - $i;
        $r .= $r ? ' ' . ($valor > 1 ? $plural[$t] : $singular[$t]) : '';

        if ($valor == '000')
            $z++;
        elseif ($z > 0)
            $z--;
        if (($t == 1) && ($z > 0) && ($inteiro[0] > 0))
            $r .= ( ($z > 1) ? ' de ' : '') . $plural[$t];

        if ($r)
            $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ', ' : ' e ') : ' ') . $r;
    }

    return ($rt ? trim($rt) : 'zero');
}

function diaSemana($dia, $mes, $ano)
{
    $semanas = array(
        'Sunday'    => 'Domingo',
        'Monday'    => 'Segunda-feira',
        'Tuesday'   => 'Terça-feira',
        'Wednesday' => 'Quarta-feira',
        'Thursday'  => 'Quinta-feira',
        'Friday'    => 'Sexta-feira',
        'Saturday'  => 'Sábado'
    );

    $semana = getDate(mktime(0, 0, 0, $mes, $dia, $ano));

    return $semanas[$semana['weekday']];
}

function semana($date)
{
    $explode = explode('-', $date);

    return diaSemana($explode[2], $explode[1], $explode[0]);
}

function dataPorExtenso($datetime, $diaDaSemana = true)
{
    $datetime   = substr($datetime, 0, 10);
    $data       = explode('-', $datetime);

    if ($diaDaSemana) {
        return diaSemana($data[2], $data[1], $data[0]) . ', ' . $data[2] . ' de ' . mb_strtolower(meses($data[1])) . ' de ' . $data[0];
    } else {
        return $data[2] . ' de ' . mb_strtolower(meses($data[1])) . ' de ' . $data[0];
    }
}

function dataPorExtensoCompleta($datetime)
{
    $hora = substr($datetime, 11, 5);

    if ($hora) {
        return dataPorExtenso($datetime, true) . ' às ' . $hora;
    } else {
        return dataPorExtenso($datetime, true);
    }
}

function sanitize($string)
{
    $string = preg_replace(array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'), array('', '-', ''), removeAcento($string));
    $string = preg_replace('/-{1,}/', '-', $string);
    $string = preg_replace('/[^a-zA-Z0-9-]/', '', html_entity_decode($string));

    return $string;
}

function removeAcento($string)
{
    $a = array('À','Á','Â','Ã','Ä','Å','Æ','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ð','Ñ','Ò','Ó','Ô','Õ','Ö','Ø','Ù','Ú','Û','Ü','Ý','ß','à','á','â','ã','ä','å','æ','ç','è','é','ê','ë','ì','í','î','ï','ñ','ò','ó','ô','õ','ö','ø','ù','ú','û','ü','ý','ÿ','Ā','ā','Ă','ă','Ą','ą','Ć','ć','Ĉ','ĉ','Ċ','ċ','Č','č','Ď','ď','Đ','đ','Ē','ē','Ĕ','ĕ','Ė','ė','Ę','ę','Ě','ě','Ĝ','ĝ','Ğ','ğ','Ġ','ġ','Ģ','ģ','Ĥ','ĥ','Ħ','ħ','Ĩ','ĩ','Ī','ī','Ĭ','ĭ','Į','į','İ','ı','Ĳ','ĳ','Ĵ','ĵ','Ķ','ķ','Ĺ','ĺ','Ļ','ļ','Ľ','ľ','Ŀ','ŀ','Ł','ł','Ń','ń','Ņ','ņ','Ň','ň','ŉ','Ō','ō','Ŏ','ŏ','Ő','ő','Œ','œ','Ŕ','ŕ','Ŗ','ŗ','Ř','ř','Ś','ś','Ŝ','ŝ','Ş','ş','Š','š','Ţ','ţ','Ť','ť','Ŧ','ŧ','Ũ','ũ','Ū','ū','Ŭ','ŭ','Ů','ů','Ű','ű','Ų','ų','Ŵ','ŵ','Ŷ','ŷ','Ÿ','Ź','ź','Ż','ż','Ž','ž','ſ','ƒ','Ơ','ơ','Ư','ư','Ǎ','ǎ','Ǐ','ǐ','Ǒ','ǒ','Ǔ','ǔ','Ǖ','ǖ','Ǘ','ǘ','Ǚ','ǚ','Ǜ','ǜ','Ǻ','ǻ','Ǽ','ǽ','Ǿ','ǿ');
    $b = array('A','A','A','A','A','A','AE','C','E','E','E','E','I','I','I','I','D','N','O','O','O','O','O','O','U','U','U','U','Y','s','a','a','a','a','a','a','ae','c','e','e','e','e','i','i','i','i','n','o','o','o','o','o','o','u','u','u','u','y','y','A','a','A','a','A','a','C','c','C','c','C','c','C','c','D','d','D','d','E','e','E','e','E','e','E','e','E','e','G','g','G','g','G','g','G','g','H','h','H','h','I','i','I','i','I','i','I','i','I','i','IJ','ij','J','j','K','k','L','l','L','l','L','l','L','l','l','l','N','n','N','n','N','n','n','O','o','O','o','O','o','OE','oe','R','r','R','r','R','r','S','s','S','s','S','s','S','s','T','t','T','t','T','t','U','u','U','u','U','u','U','u','U','u','U','u','W','w','Y','y','Y','Z','z','Z','z','Z','z','s','f','O','o','U','u','A','a','I','i','O','o','U','u','U','u','U','u','U','u','U','u','A','a','AE','ae','O','o');
    return str_replace($a, $b, $string);
}

function parse_size($size)
{
    $unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
    $size = preg_replace('/[^0-9\.]/', '', $size);

    if ($unit) {
        return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
    } else {
        return round($size);
    }
}

function uploadMaxFilesize()
{
    $size   = (float) configuracao('upload-max-filesize');
    $ini    = parse_size(ini_get('upload_max_filesize'));

    if ($size > 0) {
        // Como o tamanho é salvo em MB converte para bytes
        $size = round($size * pow(1024, 2));
    }

    if ($size > $ini) {
        $size = $ini;
    }

    return $size;
}

function formatBytes($size, $precision = 2)
{
    if ($size == 0) {
        return 0;
    }

    $base       = log($size, 1024);
    $suffixes   = array('bytes', 'KB', 'MB', 'GB', 'TB');

    return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
} 

function elapsed_time($first, $second = null, $inverse = false, $short = false, $agora = null)
{
    $first = DateTime::createFromFormat('Y-m-d H:i:s', $first);

    if (is_null($second)) {
        $second = new DateTime();
    } else {
        $second = DateTime::createFromFormat('Y-m-d H:i:s', $second);
    }

    if ($inverse) {
        $diff = $first->diff($second);
    } else {
        $diff = $second->diff($first);
    }

    if ($diff->y > 0) {
        if ($diff->y == 1) {
            if ($short) {
                return '+1a';
            } else {
                return 'Mais de 1 ano';
            }
        } else {
            if ($short) {
                return '+' . $diff->y . 'a';
            } else {
                return 'Mais de ' . $diff->y . ' anos';
            }
        }
    }

    if ($diff->m > 0) {
        if ($diff->m == 1) {
            if ($short) {
                return '+1m';
            } else {
                return 'Mais de 1 mês';
            }
        } else {
            if ($short) {
                return '+' . $diff->m . 'm';
            } else {
                return 'Mais de ' . $diff->m . ' meses';
            }
        }
    }

    if ($diff->d > 0) {
        if ($diff->d == 1) {
            if ($short) {
                return '+1 dia';
            } else {
                return 'Mais de 1 dia';
            }
        } else {
            if ($short) {
                return '+' . $diff->d . ' dias';
            } else {
                return 'Mais de ' . $diff->d . ' dias';
            }
        }
    }

    if ($diff->h > 0) {
        if ($diff->h == 1) {
            if ($diff->i <= 1) {
                if ($short) {
                    return '1h';
                } else {
                    return '1 hora';
                }
            } else {
                if ($short) {
                    return '1h' . $diff->i . 'm';
                } else {
                    return '1 hora e ' . $diff->i . ' minutos';
                }
            }
        } else {
            if ($diff->i <= 1) {
                if ($diff->i == 1) {
                    if ($short) {
                        return $diff->h . 'h1m';
                    } else {
                        return $diff->h . ' horas e 1 minuto';
                    }
                } else {
                    if ($short) {
                        return $diff->h . 'h';
                    } else {
                        return $diff->h . ' horas';
                    }
                }
            } else {
                if ($short) {
                    return $diff->h . 'h' . $diff->i . 'm';
                } else {
                    return $diff->h . ' horas e ' . $diff->i . ' minutos';
                }
            }
        }
    }

    if ($diff->i > 0) {
        if ($diff->i == 1) {
            if ($short) {
                return '1min';
            } else {
                return '1 minuto';
            }
        } else {
            if ($short) {
                return $diff->i . 'min';
            } else {
                return $diff->i . ' minutos';
            }
        }
    }

    return $agora;
}

function moduloAtivo()
{
    return Registry::get('modulo.ativo');
}

function mySoundex($value)
{
    $temp       = explode(' ', $value);
    $soundex    = '';

    foreach ($temp as $item) {
        $item = str_replace("'", "\'", $item);
        $soundex .= soundex($item);
    }

    return $soundex;
}

function uploadFoto($imagebase64, $folder, $filename, $width = null, $height = null, $public = true)
{
    $img = $imagebase64;

    list($type, $img) = explode(';', $img);
    list(, $img) = explode(',', $img);

    $img        = base64_decode($img);
    $local_dir  = Storage::disk('public')->path('');

    file_put_contents($local_dir . $filename, $img);

    $image = Image::make($local_dir . $filename);

    $image->resize($width, $height, function($constraint) {
        //$constraint->aspectRadio();
        $constraint->upsize();
    });

    $image->save($local_dir . $filename);

    $disk = Storage::disk(env('FILESYSTEM_DISK'));

    if ($disk->put($folder . $filename, file_get_contents($local_dir . $filename))) {
        if ($public) {
            $disk->setVisibility($folder . $filename, 'public');
        }

        Storage::disk('public')->delete($filename);

        return true;
    }

    return false;
}

function situacoesContaFinanceiro($situacao = null)
{
    $situacoes = array(
        0 => 'Em aberto',
        1 => 'Baixada',
        2 => 'Cancelada'
    );

    if (null === $situacao) {
        return $situacoes;
    }

    return $situacoes[$situacao];
}
