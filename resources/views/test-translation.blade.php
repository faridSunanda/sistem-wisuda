<!DOCTYPE html>
<html>
<head>
    <title>Test Translation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">🔤 Test Google Translate Integration</h1>
        
        <!-- Connection Status -->
        <div class="card mb-4">
            <div class="card-header">
                Connection Status
            </div>
            <div class="card-body">
                @if($connectionTest['success'])
                    <div class="alert alert-success">
                        ✅ {{ $connectionTest['message'] }}
                        <br><small>Response time: {{ $connectionTest['response_time'] }}</small>
                    </div>
                    <p><strong>Test:</strong> "{{ $connectionTest['test_text'] }}"</p>
                    <p><strong>Result:</strong> "{{ $connectionTest['result'] }}"</p>
                @else
                    <div class="alert alert-warning">
                        ⚠️ {{ $connectionTest['message'] }}
                        <br><small>Error: {{ $connectionTest['error'] }}</small>
                    </div>
                    <p>Using fallback glossary translation.</p>
                @endif
            </div>
        </div>
        
        <!-- Test Results -->
        <div class="card">
            <div class="card-header">
                Translation Test Results
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th width="50%">Indonesian</th>
                            <th width="50%">English (Translated)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($results as $result)
                        <tr>
                            <td>{{ $result['indonesian'] }}</td>
                            <td>{{ $result['english'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Live Test -->
        <div class="card mt-4">
            <div class="card-header">
                Live Translation Test
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Enter Indonesian certificate name:</label>
                    <input type="text" id="testInput" class="form-control" 
                           placeholder="Contoh: Sertifikat Pelatihan Laravel">
                </div>
                <button onclick="testTranslation()" class="btn btn-primary">Translate</button>
                
                <div id="result" class="mt-3" style="display: none;">
                    <div class="alert alert-info">
                        <strong>Result:</strong> <span id="translationResult"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    function testTranslation() {
        const input = document.getElementById('testInput').value;
        if (!input.trim()) {
            alert('Please enter text to translate');
            return;
        }
        
        fetch('/api/translate-test', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ text: input })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('translationResult').textContent = data.translated;
                document.getElementById('result').style.display = 'block';
            } else {
                alert('Translation failed');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Translation failed');
        });
    }
    </script>
</body>
</html>