<?php

namespace Filament\Schema\Components\Wizard;

use Closure;
use Filament\Schema\Components\Component;
use Filament\Schema\Components\Contracts\CanConcealComponents;
use Illuminate\Support\Str;

class Step extends Component implements CanConcealComponents
{
    protected ?Closure $afterValidation = null;

    protected ?Closure $beforeValidation = null;

    protected string | Closure | null $description = null;

    protected string | Closure | null $icon = null;

    /**
     * @var view-string
     */
    protected string $view = 'filament-schema::components.wizard.step';

    final public function __construct(string $label)
    {
        $this->label($label);
    }

    public static function make(string $label): static
    {
        $static = app(static::class, ['label' => $label]);
        $static->configure();

        return $static;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->key(fn (Step $component): string => Str::slug($component->getLabel()));
    }

    public function afterValidation(?Closure $callback): static
    {
        $this->afterValidation = $callback;

        return $this;
    }

    /**
     * @deprecated Use `afterValidation()` instead.
     */
    public function afterValidated(?Closure $callback): static
    {
        $this->afterValidation($callback);

        return $this;
    }

    public function beforeValidation(?Closure $callback): static
    {
        $this->beforeValidation = $callback;

        return $this;
    }

    public function description(string | Closure | null $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function icon(string | Closure | null $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function callAfterValidation(): void
    {
        $this->evaluate($this->afterValidation);
    }

    public function callBeforeValidation(): void
    {
        $this->evaluate($this->beforeValidation);
    }

    public function getDescription(): ?string
    {
        return $this->evaluate($this->description);
    }

    public function getIcon(): ?string
    {
        return $this->evaluate($this->icon);
    }

    /**
     * @return array<string, int | null>
     */
    public function getColumnsConfig(): array
    {
        return $this->columns ?? $this->getContainer()->getColumnsConfig();
    }

    public function canConcealComponents(): bool
    {
        return true;
    }
}
