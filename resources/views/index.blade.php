@extends('office::layout')
@section('content')
    <div id="root" class="row"></div>
@endsection
@section('js')
    <script src="{{ 'https://unpkg.com/react@18/umd/react.development.js' }}"></script>
    <script src="{{ 'https://unpkg.com/react-dom@18/umd/react-dom.development.js' }}"></script>
    <script src="{{ 'https://unpkg.com/@babel/standalone/babel.min.js' }}"></script>
    <script type="text/javascript" src="{{ $config['js'] . '/web-apps/apps/api/documents/api.js' }}"></script>

    <script type="text/babel">
        const root = ReactDOM.createRoot(document.getElementById("root"));
        const conf = @json($config);
        let validationErrors = [];

        function App() {
            const [isLoading, setLoading] = React.useState(true);

            // OnDocument loaded
            conf.events['onDocumentReady'] = () => {
                setLoading(false)
            };

            // Events
            conf.events['onDocumentStateChange'] = function (event) {
                if (event.data) {
                    setLoading(true);
                } else {
                    setLoading(false)
                }
            }

            React.useEffect(() => {
                // Setting configuration to onlyOffice object
                new DocsAPI.DocEditor("workspace", conf);
            }, []);

            async function save(e) {
                e.preventDefault();

                const data = {
                    c: 'forceSave'.toLowerCase(),
                    key: conf.document.key
                };

                if (isLoading) {
                    return
                }

                setLoading(true)

                try {
                    const response = await fetch("{{ route('only-office.command') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(data)
                    });

                    setLoading(false)

                    if (!response.ok) {
                        throw new Error("Network response was not OK");
                    }

                    let result = await response.json();
                    const error = result.data?.result?.error

                    if (error === 0) {
                        alert('Successfully saved to storage.')

                        setTimeout(() => {
                            window.location.reload()
                        }, 2000);
                        return
                    }

                    if (error === 1) {
                        return alert('Document key is missing or no document with such key could be found.')
                    }

                    if (error === 4) {
                        alert('No changes were applied to the document to save.')
                        return
                    }

                    alert('Unexpected error occurs')
                } catch (error) {
                    setLoading(false);
                }
            }

            return (
                <>

                    <div className="col-3">
                        <div className="card m-2 mt-0">
                            <div className="card-body p-3">
                                <form onSubmit={save} className="p-2">
                                    <div className="row">
                                        <label htmlFor="description"
                                               className="px-0 @error('description') text-danger @enderror">{{ __('Description') }}</label>
                                        <textarea
                                            className="form-control @error('description') is-invalid @enderror"
                                            name="description" id="description"
                                            cols="30" rows="2"></textarea>

                                        @error('description')
                                        <div className="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div className="row mt-3">
                                        {
                                            isLoading ? (
                                                    <button title="isLoading" className="btn btn-primary" type="button"
                                                            disabled>
                                        <span className="spinner-grow spinner-grow-sm" role="status"
                                              aria-hidden="true">
                                        </span>
                                                        <span>{{ __('Loading') }}...</span>
                                                    </button>
                                                )
                                                :
                                                (
                                                    <button title="!isLoading || 1" type="submit"
                                                            className="btn btn-primary mx-auto">
                                                        {{ __('Save') }}
                                                    </button>
                                                )
                                        }
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div className="col-9">
                        <div id="workspace"></div>
                    </div>
                </>
            )
        }

        root.render(<App/>);
    </script>
@endsection