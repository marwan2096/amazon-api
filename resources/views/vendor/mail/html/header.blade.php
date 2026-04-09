@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://upload.wikimedia.org/wikipedia/commons/a/a9/Amazon_logo.svg" class="logo" alt="Amazon Logo">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
