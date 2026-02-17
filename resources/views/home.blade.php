@extends('layouts.app')

@section('content')
<style>
    .main-menu-container {
        min-height: 80vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .menu-card {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        color: #fff;
        border-radius: 20px;
        box-shadow: 0 8px 24px rgba(34, 139, 34, 0.2);
        width: 260px;
        height: 220px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        margin: 0 30px;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .menu-card:hover {
        transform: translateY(-8px) scale(1.04);
        box-shadow: 0 16px 32px rgba(34, 139, 34, 0.25);
    }
    .menu-title {
        background: #222;
        padding: 18px 32px;
        border-radius: 12px;
        font-size: 1.5rem;
        font-weight: bold;
        text-align: center;
        font-style: italic;
        letter-spacing: 1px;
    }
    @media (max-width: 700px) {
        .main-menu-container {
            flex-direction: column;
        }
        .menu-card {
            margin: 20px 0;
        }
    }
</style>
<div class="container main-menu-container">
    <div class="menu-card" onclick="window.location.href='{{ route('apriori.redirect') }}'">
        <div class="menu-title">Analisis<br>Apriori</div>
    </div>
    <div class="menu-card" onclick="window.open('https://lm7mtv-muhammad0dzaka0murran0rusid.shinyapps.io/caplot-app/', '_blank')">
        <div class="menu-title">Analisis<br>Biplot</div>
    </div>
</div>
@endsection 