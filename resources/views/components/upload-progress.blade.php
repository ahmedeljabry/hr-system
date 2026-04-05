@props(['progress' => 0])

<div class="w-full bg-gray-200 rounded-full h-2.5 rtl:flip">
  <div class="bg-primary h-2.5 rounded-full transition-all duration-300 ease-out" 
       :style="`width: ${{{ $progress }}}%`">
  </div>
</div>
