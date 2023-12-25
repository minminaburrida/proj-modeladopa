using Habitaciones;
using Auth;
var builder = WebApplication.CreateBuilder(args);
var app = builder.Build();

app.MapGet("/", () => "Hello World!");

app.MapGet("/habitaciones/", context => HabitacionController.Lista(context));
app.MapGet("/habitaciones/edit", context => HabitacionController.Edit(context));
app.MapPost("/auth/login", async context => await UsuarioController.Login(context));

app.Run();
