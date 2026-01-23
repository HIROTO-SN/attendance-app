<div>
    <div class="flex justify-end mb-4">
        <a href="{{ route('requests.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded">
            申請作成
        </a>
    </div>

    <table class="w-full border">
        <thead>
            <tr class="bg-gray-100">
                <th>申請日</th>
                <th>種別</th>
                <th>対象日</th>
                <th>状態</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requests as $request)
            <tr class="border-t">
                <td>{{ $request->created_at->format('m/d') }}</td>
                <td>{{ $request->type }}</td>
                <td>{{ optional($request->target_date)->format('m/d') }}</td>
                <td>
                    <span class="px-2 py-1 text-xs rounded
                        {{ $request->status === 'pending' ? 'bg-yellow-200' : 'bg-green-200' }}">
                        {{ $request->status }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>