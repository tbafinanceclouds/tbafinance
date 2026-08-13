<div style="text-align: center; border-bottom: 3px solid #333; padding-bottom: 15px; margin-bottom: 20px;">
    @if(isset($company) && $company->logo)
        <img src="{{ public_path('storage/' . $company->logo) }}" alt="{{ $company->name }}" style="max-height: 60px; margin-bottom: 5px;">
    @endif
    <h1 style="font-size: 20px; margin: 0; color: #333;">{{ isset($company) ? $company->name : auth()->user()->company->name }}</h1>
    <p style="margin: 2px 0; font-size: 12px; color: #666;">
        {{ isset($company) ? $company->address : auth()->user()->company->address }}<br>
        {{ isset($company) ? $company->phone : auth()->user()->company->phone }} | {{ isset($company) ? $company->email : auth()->user()->company->email }}
    </p>
    <h2 style="font-size: 16px; color: #555; margin: 5px 0;">{{ $title ?? 'Report' }}</h2>
    <p style="font-size: 11px; color: #999; margin: 0;">Generated: {{ now()->format('Y-m-d H:i') }}</p>
</div>