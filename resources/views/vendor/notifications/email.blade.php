@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('Whoops!')
@else
# @lang('Merhaba!')
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
switch ($level) {
    case 'success':
    case 'error':
        $color = $level;
        break;
    default:
        $color = 'primary';
}
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
En iyi dileklerimizle,<br>
{{ config('app.name') }}
@endif

{{-- Subcopy --}}
@isset($actionText)
@slot('subcopy')

Uygulamaya dair kullanma kılavuzuna erişmek için <a href="https://www.ktu.edu.tr/dosyalar/ofyazilim_8def6.pdf">tıklayınız</a>.
Geri bildirimlerinizi ym@ktu.edu.tr veya htolgakahraman@ktu.edu.tr 
adreslerine gönderebilirsiniz. Verdiğiniz destekten ötürü teşekkür ederiz.<br><br>
<i>
    @lang(
    "Eğer \":actionText\" butonunu tıklamakta sorun yaşıyorsanız, URL yi kopyalayarak\n".
    'web tarayıcınıza yapıştırınız:',
    [
    'actionText' => $actionText,
    ]
    )
    <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span><br>
</i>
@endslot
@endisset
@endcomponent
