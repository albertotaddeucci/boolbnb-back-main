<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Session;

class Handler extends ExceptionHandler
{
    /**
     * Una lista delle eccezioni che non devono essere riportate.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * Una lista delle eccezioni per le quali non deve essere eseguito il logging.
     *
     * @var array<string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Registra i callback per la gestione delle eccezioni.
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Esegue il rendering di un'eccezione in una risposta HTTP.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function render($request, Throwable $e)
    {
        // Se l'eccezione è del tipo NotFoundHttpException (pagina non trovata), restituisci una risposta HTTP 404 con un messaggio personalizzato.
        if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            $errorMessage = "Hai tentato di raggiungere una pagina inesistente";
            return response()->view('errors.404', ['message' => $errorMessage], 404);
        }

        // Se l'eccezione è del tipo AccessDeniedHttpException (accesso negato), restituisci una risposta HTTP 403 con un messaggio personalizzato.
        if ($e instanceof \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException) {
            $errorMessage = "Accesso Negato";
            return response()->view('errors.404', ['message' => $errorMessage], 403);
        }

        // Se l'eccezione è una generica HttpException o QueryException, restituisci una risposta HTTP 500 con un messaggio personalizzato.
        if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException || $e instanceof QueryException) {
            $errorMessage = "Hai inserito un nome già esistente";

            // Usa la facciata Session per aggiungere il messaggio di errore alla sessione
            Session::flash('error', $errorMessage);

            // Utilizza response()->back() per reindirizzare l'utente alla pagina precedente e mantenere l'input
            return back()->withInput();
        }

        return parent::render($request, $e);
    }
}
