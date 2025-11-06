use DBJTGDWESProyectoTema4;

insert into T02_Departamento (T02_CodDepartamento,T02_DescDepartamento,T02_FechaCreacionDepartamento,T02_VolumenDeNegocio,T02_FechaBajaDepartamento)
values
    ('TES','Desc test', NOW() - INTERVAL 3 MONTH, 1235.5, NOW() - INTERVAL 43 DAY),
    ('INF','Dept Informatica', NOW() - INTERVAL 2 WEEK, 1235.5 ,NULL),
    ('MUS','Dept Musica', NOW(), 1235.5, NULL)
;