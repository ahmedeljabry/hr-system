<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Avatar extends Component
{
    public string $name;
    public ?string $image;
    public string $size;

    public function __construct(string $name, ?string $image = null, string $size = 'md')
    {
        $this->name = $name;
        $this->image = $image;
        $this->size = $size;
    }

    public function getInitials(): string
    {
        $words = explode(' ', trim($this->name));
        $initials = '';
        foreach ($words as $word) {
            $initials .= mb_substr($word, 0, 1);
            if (mb_strlen($initials) >= 2) {
                break;
            }
        }
        return mb_strtoupper($initials);
    }

    public function getColorHex(): string
    {
        $hash = md5($this->name);
        // Map hash to a hue (0-360)
        $hue = hexdec(substr($hash, 0, 3)) % 360;
        return "hsl({$hue}, 70%, 40%)";
    }

    public function render(): View|Closure|string
    {
        return view('components.avatar');
    }
}
