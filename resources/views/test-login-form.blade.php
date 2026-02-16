<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Login Debug</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h1 class="text-2xl font-bold mb-6 text-center">Test Login Debug</h1>
        
        <form id="testForm" class="space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Credential (ID or Email)</label>
                <input type="text" name="credential" value="u0001" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                <p class="text-xs text-gray-500 mt-1">Try: u0001 or test@example.com</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" value="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                <p class="text-xs text-gray-500 mt-1">Default: password</p>
            </div>
            
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 rounded-lg transition">
                Test Login
            </button>
        </form>
        
        <div id="response" class="mt-6 p-4 rounded-lg hidden" style="display: none;">
            <h2 class="font-bold mb-2">Response:</h2>
            <pre id="responseText" class="text-sm bg-gray-100 p-3 rounded overflow-auto max-h-64 whitespace-pre-wrap break-words"></pre>
        </div>
    </div>

    <script>
        document.getElementById('testForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const credential = document.querySelector('input[name="credential"]').value;
            const password = document.querySelector('input[name="password"]').value;
            const token = document.querySelector('input[name="_token"]').value;
            
            try {
                const response = await fetch('/test-login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({ credential, password })
                });
                
                const data = await response.json();
                
                const responseDiv = document.getElementById('response');
                const responseText = document.getElementById('responseText');
                
                responseText.textContent = JSON.stringify(data, null, 2);
                responseDiv.style.display = 'block';
                
                // Color code based on success
                if (data.authenticated) {
                    responseDiv.className = 'mt-6 p-4 rounded-lg bg-green-100 border border-green-400';
                } else {
                    responseDiv.className = 'mt-6 p-4 rounded-lg bg-red-100 border border-red-400';
                }
            } catch (error) {
                document.getElementById('response').style.display = 'block';
                document.getElementById('responseText').textContent = 'Error: ' + error.message;
                document.getElementById('response').className = 'mt-6 p-4 rounded-lg bg-red-100 border border-red-400';
            }
        });
    </script>
</body>
</html>
