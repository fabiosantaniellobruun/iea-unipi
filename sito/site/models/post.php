<?php

use Kirby\Cms\Page;

/** Post della bacheca */
class PostPage extends Page
{
    /** Pagina della categoria scelta nel post (Avvisi, Eventi, Bandi e opportunità) */
    public function categoryPage(): ?Page
    {
        return $this->parent()?->children()->find($this->category()->value());
    }

    public function isCall(): bool
    {
        return $this->category()->value() === 'bandi-e-opportunita' && $this->deadline()->isNotEmpty();
    }

    public function startTime(): ?int
    {
        return $this->start()->isNotEmpty() ? $this->start()->toDate() : null;
    }

    public function endTime(): ?int
    {
        return $this->end()->isNotEmpty() ? $this->end()->toDate() : null;
    }

    /** Ultimo giorno che conta per il post: fine o inizio dell'evento, oppure scadenza */
    public function lastDay(): ?int
    {
        $days = array_filter([$this->endTime(), $this->startTime(), $this->deadline()->isNotEmpty() ? $this->deadline()->toDate() : null]);
        return $days === [] ? null : strtotime('today', max($days));
    }

    /** In bacheca e non in archivio: pubblicato nell'ultimo anno, o con un evento o una scadenza ancora da venire */
    public function isCurrent(): bool
    {
        $today = strtotime('today');
        return ($this->lastDay() ?? 0) >= $today || $this->date()->toDate() >= strtotime('-1 year', $today);
    }

    /** Ancora da mostrare tra gli avvisi della home: evento o scadenza passati da meno di 30 giorni, o post dell'ultimo anno */
    public function isFresh(): bool
    {
        $today = strtotime('today');
        return $this->lastDay() !== null
            ? $this->lastDay() >= strtotime('-30 days', $today)
            : $this->date()->toDate() >= strtotime('-1 year', $today);
    }

    /** Data o periodo dell'evento: "22 settembre 2026", "23-24 ottobre 2025"; con $long anche giorno della settimana e ora */
    public function when(bool $long = false): ?string
    {
        $start = $this->startTime();
        if ($start === null) {
            return null;
        }

        $end = $this->endTime();
        if ($end === null || date('Y-m-d', $end) === date('Y-m-d', $start)) {
            $label = formatDate($start, $long ? 'EEEE d MMMM y' : 'd MMMM y');
            $time  = date('H:i', $start);
            return $long && $time !== '00:00' ? $label . ', ' . trim(t('post.time') . ' ' . $time) : $label;
        }

        $first = match (true) {
            date('Y-m', $start) === date('Y-m', $end) => formatDate($start, 'd'),
            date('Y', $start) === date('Y', $end)     => formatDate($start, 'd MMMM'),
            default                                   => formatDate($start, 'd MMMM y'),
        };

        return $first . '–' . formatDate($end, 'd MMMM y');
    }

    /** Data mostrata nelle card: scadenza per i bandi, data dell'evento, altrimenti data di pubblicazione */
    public function cardDate(): string
    {
        if ($this->isCall() === true) {
            $deadline = $this->deadline()->toDate();
            return t($deadline < strtotime('today') ? 'post.expired' : 'post.deadline') . ' ' . formatDate($deadline);
        }

        return $this->when() ?? formatDate($this->date()->toDate());
    }
}
