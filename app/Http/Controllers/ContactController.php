<?php

namespace App\Http\Controllers;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{

    //
     // Récupérer tous les contacts
     public function index()
     {
        //  return response()->json(Contact::all());
         return Contact::all();
     }
 
     // Créer un nouveau contact
     public function store(Request $request)
     {
         $request->validate([
             'name' => 'required|string|max:255',
             'email' => 'required|email|unique:contacts,email',
             'phone' => 'nullable|string|max:15',
             'message' => 'required|string',
         ]);
 
      return Contact::create($request->all());
     }
 
     // Récupérer un contact spécifique
     public function show($id)
     {
        return Contact::find($id);
     }
 
     // Mettre à jour un contact existant
     public function update(Request $request, $id)
     {
        $contact = Contact::find($id);
        $contact->update($request->all());
        return $contact;
     }
 
     // Supprimer un contact
     public function destroy($id)
     {
         $contact = Contact::findOrFail($id);
         $contact->delete();
 
         return response()->json(null, 204);
     }
}


