using Habitaciones;
using Auth;

var MyAllowSpecificOrigins = "_myAllowSpecificOrigins";
var builder = WebApplication.CreateBuilder(args);

builder.Services.AddCors(options =>
{
    options.AddPolicy(name: MyAllowSpecificOrigins,
        policy =>
        {
            policy.AllowAnyOrigin().AllowAnyHeader().AllowAnyMethod();
        });
});


var app = builder.Build();
app.UseCors(MyAllowSpecificOrigins); // Add this line
app.MapGet("/", () => "Hello World!");

app.MapGet("/habitaciones/", context => HabitacionController.Lista(context));
app.MapGet("/habitaciones/edit", context => HabitacionController.Edit(context));
app.MapPost("/auth/login", async context => await UsuarioController.Login(context));
app.MapPost("/auth/iam", async context => UsuarioController.IAmIve(context));

app.Run();
