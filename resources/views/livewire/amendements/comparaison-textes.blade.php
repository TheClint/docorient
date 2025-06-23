<div class="grid grid-cols-2 gap-4">
    
    <div class="bg-gray-200 p-4">
        <h4 class="flex text-xl font-bold mb-2 justify-center">Texte original</h4>
        @foreach ($segmentsAvant as $segment)
            @if($segment->texte[0] == "\n")
                </span> 
                    @for ($i = 0; $i <= strlen($segment->texte); $i++)
                        <div class="w-full"><br></div>
                    @endfor
                </span>
            @else
                {!! nl2br(e($segment->texte)) !!}
            @endif
        @endforeach
        {!! nl2br($formattedTextOriginal) !!}
        @foreach ($segmentsApres as $segment)
            @if($segment->texte[0] == "\n")
                </span> 
                    @for ($i = 0; $i <= strlen($segment->texte); $i++)
                        <div class="w-full"><br></div>
                    @endfor
                </span>
            @else
                {!! nl2br(e($segment->texte)) !!}
            @endif
        @endforeach
    </div>

    <div class="bg-gray-200 p-4">
        <h4 class="flex justify-center text-xl font-bold mb-2">Texte amendé</h4>
        @foreach ($segmentsAvant as $segment)
            @if($segment->texte[0] == "\n")
                </span> 
                    @for ($i = 0; $i <= strlen($segment->texte); $i++)
                        <div class="w-full"><br></div>
                    @endfor
                </span>
            @else
                {!! nl2br(e($segment->texte)) !!}
            @endif
        @endforeach
        {!! nl2br($formattedTextAmende) !!}
        @foreach ($segmentsApres as $segment)
            @if($segment->texte[0] == "\n")
                </span> 
                    @for ($i = 0; $i <= strlen($segment->texte); $i++)
                        <div class="w-full"><br></div>
                    @endfor
                </span>
            @else
                {!! nl2br(e($segment->texte)) !!}
            @endif
        @endforeach
    </div>
</div>